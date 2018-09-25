<?php

namespace App\Http\Controllers;
use App;
use Illuminate\Support\Facades\DB;
use App\Manufacturer;
use App\StockUnit;
use App\Product;
use App\ProductCategory;
use App\StockAdjustReason;
use App\StockAdjustHistory;
use App\StockAdjustHistoryEntry;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index() {
        $products = Product::with('stock_units')->with('category')->paginate(10);
        $categories = ProductCategory::all();
        return view('inventory.index', compact(['products', 'categories']));
    }

    public function searchProduct(Request $request) {
        $filter = $request->query('filter');
        // $products = DB::table('products')->where('name', 'like', $filter.'%')->paginate(15);
        $products = Product::where('name', 'LIKE', '%' . $filter . '%')->paginate(15);
        $categories = ProductCategory::all();
        $manufacturers = Manufacturer::all();
        return view('inventory.product.index', compact(['products', 'manufacturers', 'categories']));  
    }


    public function addStockUnitToProduct(Request $request, $productId)
    {
        $input = $request->all();
        $stock_unit_id = $input['stock_unit_id'];
        $product = Product::findOrFail($productId);
        $result = [];
        if($product->stock_units()->syncWithoutDetaching($stock_unit_id)) {
            $result['code']=0;
        } else {
            $result['code'] = 1;
        }
        return response()->json($result);
    }


    public function removeStockUnitFromProduct(Request $request, $productId, $stockUnitId) 
    {
        $product = Product::findOrFail($productId);
        $result = [];
        if($product->stock_units()->detach($stockUnitId)) {
            $result['code'] = 0;
        } else {
            $result['code'] = 1;
        }
        return response()->json($result);
    }

    public function productStockPriceIndex(Request $request)
    {
        $products = Product::paginate(15);
        $depot = $request->user();
        return view('inventory.product-depot-stock', compact('products'));
    }

    public function getStockUnitForProduct($productId, $stockUnitId)
    {
        $productStock = DB::table('product_stock_units')->where('product_id', $productId)
                                                        ->where('stock_unit_id',$stockUnitId)->get();
        $stockDetails = DB::table('depot_product_stock_unit')
                                        ->where('product_stock_unit_id',$productStock[0]->id)->get();
        $stockUnits = StockUnit::all();                                
        $stockDict = [];
        foreach($stockDetails as $stock) {
            
            //$stockDict['']
        }              
        return response()->json($stockDetails);                                                
    }

    public function updateStockPrice(Request $request, $productId, $skuId) 
    {
        //var_dump($request->all());
        $request->validate([
            'selling_price'=>'required'
        ]);
        $input = $request->all();
        $sellingPrice = $input['selling_price']*100;
        $product = Product::findOrFail($productId);
        $productUpdate = $product->stock_units()->where('stock_unit_id', $skuId)
                                ->updateExistingPivot($skuId, ['selling_price'=>$sellingPrice]);
        $result = [];
        $result['code'] = 0;
        return response()->json($result);
        
    }

    public function updateStockQuantity(Request $request, $product, $stockunit)
    {
        $input = $request->all();
        $stock = DB::table('product_stock_units')->where('product_id', $product)->where('stock_unit_id', $stockunit)->first();
        $stockQUantity = $stock->stock_quantity;
        $newQuantity = $stockQUantity + $input['quantity'];
        DB::table('product_stock_units')->where('product_id', $product)->where('stock_unit_id', $stockunit)->update(['stock_quantity'=>$newQuantity]);
        return response()->json(['code'=>0, 'message'=>'Stock quantity updated']);
    }

    public function inventorySearch(Request $request)
    {
        $filter = $request->query('filter');
        $products = Product::where('name', 'LIKE', '%' . $filter . '%')->paginate(15);
        return view('inventory.index', compact('products'));  
    } 

    public function indexStockAdjustment()
    {
        $reasons = StockAdjustReason::all();
        $date = new Carbon();
        $date2 = new Carbon();
        $historyRef = StockAdjustHistory::whereBetween('created_at', [$date->startOfDay(), $date2->endOfDay()])->count();
        $historyRef = 'STOCK-A-'.$date->toDateString().'/'.($historyRef+1);
        return view('inventory.stock-adjustment', compact(['reasons', 'historyRef']));
    }

    public function postAdjustStock(Request $request) 
    {
        // $request->validate([
        //     'reference_code'=> 'required|unique:users'
        // ]);
        DB::transaction(function () {
            $input = Input::all();
            $adjust = new StockAdjustHistory();
            $adjust->reference_code = $input['reference_code'];
            $adjust->reason_id = $input['reason'];
            $adjust->creator_id = Auth::user()->id;
            $adjust->save();
            $adjustId = $adjust->id;
            $entries = $input['entries'];
            foreach($entries as $entry) {
                $aentry = new StockAdjustHistoryEntry();
                $aentry->product_id = $entry['product_id'];
                $aentry->stock_unit_id = $entry['stock_unit_id'];
                $aentry->history_id = $adjustId;
                $aentry->old_quantity = $entry['old_quantity'];
                $aentry->new_quantity = $entry['new_quantity'];
                $aentry->quantity = $entry['difference'];
                $aentry->cost_price = $entry['cost_price'];
                $aentry->selling_price = $entry['selling_price'];
                $aentry->save();

                DB::table('product_stock_units')->where('product_id', $entry['product_id'])
                                            ->where('stock_unit_id', $entry['stock_unit_id'])
                                            ->update(['stock_quantity'=> $entry['new_quantity']]);
            }
        });
        $result = ['code'=>0];
        return response()->json($result);   
    }


    public function searchProductJson(Request $request) 
    {
        $filter = $request->query('filter');
        $products = Product::where('name', 'LIKE', '%' . $filter . '%')
                            ->with('stock_units')->get();
        $result = ['code'=>0, 'products'=>$products];
        return response()->json($result);
    } 

    public function createAdjustStockReason(Request $request) 
    {
        $input = $request->all();
        $reason = new StockAdjustReason();
        $reason->label = $input['label'];
        $reason->description = $input['description'];
        $result = [];
        $reason->save();
        $result['code'] = 0;
        $reasons = StockAdjustReason::all();
        $result['reasons'] = $reasons;
        return response()->json($result);
    }
}
