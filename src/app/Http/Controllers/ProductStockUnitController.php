<?php

namespace App\Http\Controllers;
use App\ProductStockUnit;
use Illuminate\Http\Request;
use App\StockUnit;

class ProductStockUnitController extends Controller
{
    //
    public function index(Request $request, $product) 
    {
        $input = $request->all();
        // $stockunit = $input['stock_unit_id'];
        // $productsus = ProductStockUnit::where('product_id', $id)->get();
        // dd($productsus);

    }

    public function exceptIndex($product) {
        $productStocks = ProductStockUnit::where('product_id', $product)->get();
    }

    public function create(Request $request, $product)
    {
        $input = $request->all();
        $myProduct = Product::findOrFail($product);
        
    }
}
