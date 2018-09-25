<?php

namespace App\Http\Controllers;

use App\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if($request->query('filter')) {
            $filter = $request->query('filter');
            $manufacturers = Manufacturer::where('name', 'LIKE', '%' . $filter . '%')->paginate(10);
            return view('inventory.manufacturer.index', compact(['manufacturers']));
        } else {
            $manufacturers = Manufacturer::paginate(10);
            return view('inventory.manufacturer.index', compact(['manufacturers']));
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
        //
        $request->validate([
            'name'=>'required|unique:manufacturers|max:100',
            'email'=>'email',
            'address'=>'max:300'
        ]);
        $input = $request->all();
        $manufacturer = new Manufacturer();
        $manufacturer->name = $input['name'];
        $manufacturer->email = $input['email'];
        $manufacturer->address = $input['address'];
        $manufacturer->phone = $input['phone'];
        $result = [];
        if($manufacturer->save()) {
            $manufacturers = DB::table('manufacturers')->select('id', 'name as label')->get();
            $result['code'] = 0;
            $result['manufacturers'] = $manufacturers;
            if($request->ajax()) {
                return response()->json($result);
            }
            return redirect()->back();
        } else {
            $result['code']=1;
            if($request->ajax()) {
                return response()->json($result);
            }
            return redirect()->back();
        }
        $request->session()->flash('message','manufacturer added successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function show(Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $manufacturer = Manufacturer::findOrFail($id);
        return view('inventory.manufacturer.edit', compact('manufacturer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name'=>'required|max:200|unique:manufacturers,name,'.$id.'id',
            'email'=>'email',
        ]);
        $input = $request->all();
        $manuf = Manufacturer::findOrFail($id);
        $manuf->name = $input['name'];
        $manuf->email = $input['email'];
        $manuf->address = $input['address'];
        $manuf->phone = $input['phone'];
        $manuf->update();
        return redirect()->route('manufacturer.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manufacturer $manufacturer)
    {
        //
    }
}
