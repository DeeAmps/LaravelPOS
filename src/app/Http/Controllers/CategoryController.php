<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App;
use App\ProductCategory;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = ProductCategory::paginate(15);
        if($request->ajax()) {
            $categories = ProductCategory::all();
            return response()->json($categories);
        } else {
            return view('inventory.category.index', compact('categories'));
        }
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
        $request->validate([
            'name'=>'required|unique:categories'
        ]);
        $input = $request->all();
        $category = new ProductCategory();
        $category->name = strtoupper($input['name']);
        if($category->save()){
            if($request->ajax()) {
                $categories = DB::table('categories')->select('id', 'name as label')->get();
                $result = ['code'=>0,'categories'=>$categories];
                return response()->json($result);
            } else {
                return redirect()->back();
            }
        } else {
            $result = ['code'=>1, 'error_message'=>'category create failed!'];
            return response()->json($result);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $category = ProductCategory::find($id);
        if($request->ajax()) {
            return response()->json($category);
        } else {
            return view('inventory.category.show', compact('category'));
        }
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
        $category = ProductCategory::findOrFail($id);
        return response()->json($category);
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
            'name'=>'required|unique:categories,name,'.$id.'id'
        ]);
        $input = $request->all();
        $category = ProductCategory::findOrFail($id);
        $category->name = strtoupper($input['name']);
        if($category->update()){
            $result = ['code'=>0];
            return response()->json($result);
        } else {
            return response()->json(['code'=>1]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = ProductCategory::find($id);
        if($category->delete()){
            $result = ['code'=>0];
            return response()->json($result);
        } else {
            $result=['code'=>1, 'error_message'=>'category delete failed!'];
            return response()->json($result);
        }
    }
}
