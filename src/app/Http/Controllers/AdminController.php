<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Product;
use App\StockUnit;
use App\Manufacturer;
use App\ProductCategory;
use BackupManager\Manager;
use Artisan;
use Illuminate\Support\Facades\Input;
// use Maatwebsite\Excel\Facades\Excel;
use Session;
use Excel;
use File;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    public function _construct(Manager $manager) {
        $this->manager = $manager;
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('admin.index', compact('users'));
    }


    public function getcsv()
    {
        return view('admin.csv-index');
    }

    public function getUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user-show', compact('user'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $result = ['code'=>1];
        return response()->json($result);
    }

    public function assignRole(Request $request, $user)
    {
        $input = $request->all();
        $myuser = User::where('id', $user)->first();
        $input = $request->all();
        $myuser->roles()->detach();
        if($request->has('sales-rep')) {
            $myuser->roles()->attach(Role::where('name', 'sales-rep')->first());
        }
        if($request->has('manager')) {
            $myuser->roles()->attach(Role::where('name', 'manager')->first());
        }
        if($request->has('admin')) {
            $myuser->roles()->attach(Role::where('name', 'admin')->first());
        }

        return redirect()->back();
    }


    public function backupIndex()
    {
        return view('admin.backup.index');
    }

    public function createBackup()
    {
        //Artisan::call("db:backup --database=mysql --destination=dropbox --destinationPath=project --timestamp='d-m-Y His' --compression=gzip");
        Artisan::call("db:backup", [
            "--database"   => "mysql",
            "--mysqlPath" => "C:\xampp\mysql\bin",
            "--compression"       => "gzip",
            "--destination" => "dropbox",
            "--destinationPath"    => "project",
            "--timestamp" => "d-m-Y His",
        ]);
        // Artisan::call('make:controller', )
        return response()->json(['code'=>0]);
    }


    public function loadcsv(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);
        if($request->hasFile('file')) {
            $extension = File::extension($request->file->getClientOriginalName());
            if($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {

                })->toArray();
                if(!empty($data)) {
                    foreach($data[0] as $key=>$value) {
                        $insert = [
                            'product' => $value["product"],
                            'category' => $value["category"],
                            'manufacturer' => $value["manufacturer"],
                            'box' => $value["box"],
                            'pack' => $value["pack"],
                            'piece_quantity' => $value["piece"],
                            'selling_price' => $value["sellin_price"]
                        ];
                        $product = new Product();
                        if($insert["product"] && $insert['category'] && $insert['manufacturer']) {
                            $product->name = $insert['product'];
                            $product->default_stock_unit = 1;
                            $categoryId = ProductCategory::where('name', $insert['category'])->first();
                            if($categoryId) {
                                $product->category_id = $categoryId->id;
                            } else {
                                $category = new ProductCategory();
                                $category->name = $insert["category"];
                                $category->save();
                                $product->category_id = $category->id;
                            }
                            $manufactuereId = Manufacturer::where('name', $insert["manufacturer"])->first();
                            if($manufactuereId) {
                                $product->manufacturer_id = $manufactuereId->id;
                            } else {
                                $manufacturer = new Manufacturer();
                                $manufacturer->name = $insert["manufacturer"];
                                $manufacturer->save();
                                $product->manufacturer_id = $manufacturer->id;
                            }
                            if(!Product::where('name', $insert['product'])->first()) {
                                $product->save();
                                $product->stock_units()->attach(StockUnit::find(1)); 
                                if($insert["selling_price"]) {
                                    $pr = Product::where('name', $insert["product"])->first();
                                    //dd($pr->id);
                                    DB::table('product_stock_units')->where('product_id', $pr->id)
                                                                ->where('stock_unit_id', 1)
                                                                ->update(['selling_price' => $insert["selling_price"]*100]);
                                }
                            } else {
                                $pp = Product::where('name', $insert["product"])->first();
                                $prodDefaultSku = DB::table('product_stock_units')->where('product_id', $pp->id)
                                                                                ->where('stock_unit_id', 1)->first();                                              
                                if(!$prodDefaultSku) {
                                   $pp->stock_units()->attach(StockUnit::find(1)); 
                                }
                                if($insert["selling_price"]) {
                                    //dd($pr->id);
                                    $price = $insert["selling_price"]*100;
                                    DB::table('product_stock_units')->where('product_id', $pp->id)
                                                                ->where('stock_unit_id', 1)
                                                                ->update(['selling_price'=>$price]);
                                }                                                
                            }    
                            $proName = Product::where('name', $insert["product"])->first();                            
                            if($insert["box"]) {
                                $str = $insert["box"]."-piece-box";
                                //var_dump($str);
                                $skuId = StockUnit::where('label', $str)->first();
                                if($skuId) {
                                    $psku = DB::table('product_stock_units')->where('product_id', $proName->id)
                                                                            ->where('stock_unit_id', $skuId->id)->first();
                                                                            //dd($psku);
                                    if(!$psku) {
                                        $proName->stock_units()->attach($skuId);
                                    } 
                                } else {
                                    $box = new StockUnit();
                                    $box->label = $str;
                                    $box->relative_sku_to_sku = $insert["box"];
                                    $box->save();
                                    $proName->stock_units()->attach($box);
                                }
                            }
                            if($insert["pack"]) {
                                $str = $insert["pack"]."-piece-pack";
                                //var_dump($str);
                                $skuId = StockUnit::where('label', $str)->first();
                                if($skuId) {
                                    $psku = DB::table('product_stock_units')->where('product_id', $proName->id)
                                                                            ->where('stock_unit_id', $skuId->id)->first();
                                                                            //dd($psku);
                                    if(!$psku) {
                                        $proName->stock_units()->attach($skuId);
                                    } 
                                } else {
                                    $box = new StockUnit();
                                    $box->label = $str;
                                    $box->relative_sku_to_sku = $insert["pack"];
                                    $box->save();
                                    $proName->stock_units()->attach($box);
                                }
                            }
                        }
                    }
                }
                Session::flash('success', 'File loaded successfully');
                return redirect()->back();
            } else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return redirect()->back();
            }
        } else {
            Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
            return redirect()->back();
        }
    }



    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}
