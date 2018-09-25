<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Product;

class ProductSearchController extends Controller
{
    //
    public function search(Request $request) 
    {
        $filter = $request->query('filter');
        $products = Product::where('barcode', 'LIKE', '%' .$filter. '%')
                                ->orWhere('name', 'LIKE', '%' . $filter . '%')
                                ->has('stock_units')
                                ->with('manufacturer')
                                ->with('stock_units')
                                ->get();
        return response()->json($products);    
    }



}
