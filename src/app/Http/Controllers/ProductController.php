<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App;
use Illuminate\Validation\Rule;
use App\ProductCategory;
use App\Product;
use App\StockUnit;
use App\Barcode;
use App\Manufacturer;
use App\ProductStockUnit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $filter = $request->query('filter');
        $products = null;
        if($filter) {
            $products = Product::where('name', 'LIKE', '%' . $filter . '%')->paginate(9);
        } else {
            $products = Product::paginate(9);
        }
        $manufacturers = Manufacturer::all();
        $categories = ProductCategory::all();
        return view('inventory.product.index', compact(['products', 'manufacturers', 'categories','stocks']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::all();
        $manufacturers = Manufacturer::all();
        return view('inventory.product.create', compact(['categories', 'manufacturers']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'bail|required|unique:products|max:255',
            'barcode'=>'bail|nullable|unique:products|max:100',
            'category_id'=> 'required|integer',
            'manufacturer_id'=>'required|integer'
        ]);
        $input = $request->all();
        $product = new Product();
        $product->name = $input['name'];
        $product->category_id = $input['category_id'];
        $product->barcode = $input['barcode'];
        $product->manufacturer_id = $input['manufacturer_id'];
        $product->description = $input['description'];
        $product->default_stock_unit = 1;
        $product->save();
        $product->stock_units()->attach(1);                          
        $result = [];                                                                           
        if($product) {
            $result['code']=0;
            $result['product_id'] = $product->id;
        } else {
            $result['code']=1;
        }
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
        $product = Product::find($id);
        $stocks = DB::table('stock_units')->select('label', 'id')->get();
        //$result = [];

        return view('inventory.product.show', compact(['product','stocks']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::all();
        $manufacturers = Manufacturer::all();
        return view('inventory.product.edit', compact(['product', 'categories', 'manufacturers']));
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
        $request->validate([
            'name'=>'bail|required|unique:products,name,'.$id.'id',
            'barcode'=>'bail|nullable|unique:products,barcode,'.$id.'id',
            'category_id'=>'required',
            'manufacturer_id'=>'required'
        ]);
        $product = Product::findOrFail($id);
        $input = $request->all();
        $product->category_id = $input['category_id'];
        $product->name = $input['name'];
        $product->barcode = $input['barcode'];
        $product->manufacturer_id = $input['manufacturer_id'];
        $product->description = $input['description'];
        $product->update();
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Product::destroy($id)){
            $result=['code'=>0,'message'=>'delete successful'];
            return response()->json($result);
        } else {
            $result=['code'=>1,'error_message'=>'delete failde'];
            return response()->json($result);
        }
    }
}
