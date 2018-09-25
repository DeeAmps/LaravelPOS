<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use App\PurchaseEntry;
use App\StockUnit;
use Auth;
use App\Product;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = DB::table('customers')->select('name as label', 'id')->get();
        return view('purchase.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'reference_code' => 'required|unique:purchases',
            'customer_id' =>'required'
        ]);
        DB::transaction(function() {
            $input = Input::all();
            $entries = $input['entries'];
            $refCode = $input['reference_code'];
            $amountPaid = $input['amount'];
            $customer = $input['customer_id'];
            $creator = Auth::user()->id;

            $purchase = new Purchase();
            $purchase->creator_id = $creator;
            $purchase->reference_code = $refCode;
            $purchase->customer_id = $customer;
            $purchase->save();
            //store entries into entries;
            foreach($entries as $entry) {
                $product = Product::find($entry['product_id']);
                $pentry = new PurchaseEntry();
                $pentry->quantity = $entry['quantity'];
                $pentry->unit_price = $entry['cost_price'];
                $pentry->purchase_id = $purchase->id;
                $pentry->product_id = $entry['product_id'];
                $pentry->stock_unit_id = $entry['stock_unit_id'];
                $pentry->save();

                //update stock quantity
                $productDefaultSku = $product->default_stock_unit;
                $defaultStockUnit = StockUnit::findOrFail($productDefaultSku);
                $skuRate = $defaultStockUnit->relative_sku_to_sku;
                $entryStockUnit = StockUnit::findOrFail($entry['stock_unit_id']);
                $entrySkuRate = $entryStockUnit->relative_sku_to_sku;
                $skuQuantity = ($entry['quantity']*$entrySkuRate)/$skuRate;
                $skuCostPrice = ($entry['cost_price']*100) / $entryStockUnit->relative_sku_to_sku;
                $stock = DB::table('product_stock_units')->where('product_id', $product->id)
                                                ->where('stock_unit_id', $productDefaultSku);
                $stock->increment('stock_quantity', $skuQuantity);
                $stock->update(['cost_price'=>$skuCostPrice]);
                $entryStock = DB::table('product_stock_units')->where('product_id', $product->id)
                                                              ->where('stock_unit_id', $entry['stock_unit_id']);                                              
                $entryStock->update(['cost_price'=> ($entry['cost_price']*100)]);                                              
                // $stockQUantity = $stock->stock_quantity + $skuQuantity;

            }
        });
        $result = ['code'=>0, 'message'=>"purchase successful"];
        return response()->json($result);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
