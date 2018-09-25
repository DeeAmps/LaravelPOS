<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Auth;
use App\Product;
use App\User;
use App\StockUnit;
use App\Sale;
use Carbon\Carbon;
use App\PaymentIn;

class PosController extends Controller
{

    public function index(Request $request) {
        $date = new Carbon();
        $date2 = new Carbon();
        $sale = Sale::whereBetween('created_at', [$date->startOfDay(), $date2->endOfDay()])->count();
        $orderRef = $date->toDateString().'/'.Auth::user()->id.'-'.($sale+1);
        return view('pos/index', compact('orderRef'));
    }

    public function search(Request $request) {
        //dd($request);
        $filter = $request->query('filter');
        $products = Product::where('barcode', 'LIKE', '%' . $filter . '%')
                                ->orWhere('name', 'LIKE', '%' . $filter . '%')
                                ->has('stock_units')
                                ->with('manufacturer')
                                ->with('stock_units')
                                ->get();
        return response()->json($products);                        
    }

    
    public function closeSaleOrder(Request $request) 
    {
        DB::transaction(function(){
            $input = Input::all();
            $creator_id = Auth::user()->id;
            $reference_code = $input['summary']['refCode'];
            $amountz = $input['summary']['total'];
            $amount  = 0;
            if(isset($amountz)) {
                $amount = $amountz * 100;
            }
            $currency_id = $input['summary']['currency_id'];
            $customer_id = 1;
            $sale = new Sale();
            $entries = $input['entries'];
            $saleId = Sale::create(['reference_code'=>$reference_code, 'customer_id'=>$customer_id
                                                            ,'creator_id'=>$creator_id]);  
           
            $totalPaid = 0;
            foreach($entries as $entry) {
                DB::table('sale_entries')->insert([
                    'sale_id'=>$saleId->id, 'product_id'=>$entry['product_id'],
                    'stock_unit_id'=>$entry['stock_unit_id'], 'unit_price'=>$entry['unit_price'],
                    'quantity'=>$entry['quantity'], 'balance'=>$entry['balance']
                ]);

                $stock = DB::table('product_stock_units')->where('product_id', $entry['product_id'])
                                                    ->where('stock_unit_id', $entry['stock_unit_id'])->first();                        
                $entryAmountPaid = $stock->selling_price * $entry['quantity'];
                $totalPaid = $totalPaid + $entryAmountPaid;
                $stockUnit = StockUnit::findOrFail($entry['stock_unit_id']); 
                $stockSkuQuantity = $stockUnit->relative_sku_to_sku * $entry['quantity'];
                DB::table('product_stock_units')->where('product_id', $entry['product_id'])
                                                ->where('stock_unit_id', 1)
                                                ->decrement('stock_quantity', $stockSkuQuantity);

            }
            $inpaymentId = DB::table('in_payments')->insert(['currency_id'=>$currency_id, 'quantity'=>$totalPaid, 'sale_id'=>$saleId->id]);                                
        });
        $result = ['code'=>0, 'message'=>'Sale trasaction successful', 'date'=>Carbon::now()];
        return response()->json($result);
    }
}
