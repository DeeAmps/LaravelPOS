<?php

namespace App\Http\Controllers;

use App\StockUnit;
use Illuminate\Http\Request;

class StockUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $stockUnits = StockUnit::all();
        if($request->ajax()) {
            $result = ['code'=>0, 'stock_units'=>$stockUnits];
            return response()->json($result);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'label' => 'required|unique:stock_units',
            'quantity' => 'required'
        ]);
        $input = $request->all();
        $sku = new StockUnit();
        $sku->label = $input['label'];
        $sku->relative_sku_to_sku = $input['quantity'];
        $reuslt = [];
        if($sku->save()) {
            $stockUnits = StockUnit::all();
            $result['code']=0;
            $result['stocks'] = $stockUnits;
            return response()->json($result);
        } else {
            $result['code'] = 1;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockUnit  $stockUnit
     * @return \Illuminate\Http\Response
     */
    public function show(StockUnit $stockUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockUnit  $stockUnit
     * @return \Illuminate\Http\Response
     */
    public function edit(StockUnit $stockUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockUnit  $stockUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockUnit $stockUnit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockUnit  $stockUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockUnit $stockUnit)
    {
        //
    }
}
