<?php

namespace App\Http\Controllers;
use App\Sale;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ReportController extends Controller
{


    public function index()
    {
        $reports = Sale::paginate(15);
        return view('report.index', compact('reports'));
    }

    public function searchReport(Request $request)
    {
        
    }

    public function getDailySale() 
    {
        $reports = Sale::with('payment')->paginate(15);
        $pay;
        $pay = DB::table('sales')->join('in_payments', 'sales.id', 'in_payments.id')->get();
        dd($pay);
        $users = User::all();
        return view('report.sale.daily-report', compact(['reports', 'users']));
    }

    public function getWeeklySale() 
    {
        $reports = Sale::with('payment')->paginate(15);
        return view('report.sale.weekly-report', compact('reports'));
    }

    public function getMonthlySale() 
    {
        $reports = Sale::with('payment')->paginate(15);
        return view('report.sale.monthly-report', compact('reports'));
    }

    public function getAnnualSale() 
    {
        $reports = Sale::with('payment')->paginate(15);
        return view('report.sale.annual-report', compact('reports'));
    }

    public function getAdvancedSale() 
    {
        $reports = Sale::with('payment')->paginate(15);
        return view('report.sale.advance-report', compact('reports'));
    }

    public function saleReport() {
        $users = User::all();
        
        $reports = DB::table('sales')
                            ->leftjoin('in_payments', 'sales.id', '=', 'in_payments.sale_id')
                            ->leftjoin('currencies', 'currencies.id', '=', 'in_payments.currency_id')
                            ->leftjoin('customers', 'customers.id', '=', 'sales.customer_id')
                            ->leftjoin('users', 'users.id', '=','sales.creator_id')
                            ->select('sales.created_at as created_on', 'sales.reference_code', 'in_payments.quantity as amount_paid'
                                        , 'currencies.symbol as currency', 'customers.name as customer', 'users.name as creator')
                            ->latest('sales.created_at')           
                            ->get();
        return view('report.sale.index', compact(['users','reports']));
    }

    public function searchSale(Request $request, Sale $sale) 
    {
        $user = $request->query('user');
        $userName = User::find($user);
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $date = $request->query('date');
        $reports = DB::table('sales')
                            ->when($user, function($query, $user) {
                                return $query->where('sales.creator_id', $user);
                            })
                            ->when($fromDate, function($query, $fromDate) {
                                $from = new Carbon($fromDate);
                                return $query->where('sales.created_at', '>=',$from);
                            })
                            ->when($toDate, function($query, $toDate) {
                                $to = new Carbon($toDate);
                                return $query->where('sales.created_at', '<=', $to);
                            })
                            ->when($date, function($query, $date) {
                                $da = new Carbon($date);
                                return $query->whereDate('sales.created_at', $da);
                            })
                            ->leftjoin('in_payments', 'sales.id', '=', 'in_payments.sale_id')
                            ->leftjoin('currencies', 'currencies.id', '=', 'in_payments.currency_id')
                            ->leftjoin('customers', 'customers.id', '=', 'sales.customer_id')
                            ->leftjoin('users', 'users.id', '=','sales.creator_id')
                            ->select('sales.id as sale_id','sales.created_at as created_on', 'sales.reference_code', 'in_payments.quantity as amount_paid'
                                        , 'currencies.symbol as currency', 'customers.name as customer', 'users.name as creator')
                            ->latest('sales.created_at')           
                            ->get();
        $total = 0;
        $entries = DB::table('sale_entries')
                            ->leftjoin('products', 'products.id', '=', 'sale_entries.product_id')
                            ->leftjoin('stock_units', 'stock_units.id', '=', 'sale_entries.stock_unit_id')
                            ->leftjoin('sales', 'sales.id', '=', 'sale_entries.sale_id')
                            ->when($user, function($query, $user) {
                                return $query->where('sales.creator_id', $user);
                            })
                            ->when($fromDate, function($query, $fromDate) {
                                $from = new Carbon($fromDate);
                                return $query->whereDate('sales.created_at', '>=',$from);
                            })
                            ->when($toDate, function($query, $toDate) {
                                $to = new Carbon($toDate);
                                return $query->whereDate('sales.created_at', '<=', $to);
                            })
                            ->when($date, function($query, $date) {
                                $da = new Carbon($date);
                                return $query->whereDate('sales.created_at', $da);
                            })
                            ->select('sale_entries.sale_id as sale_id','products.name as product_name', 'stock_units.label as stock_unit_name'
                                        ,'sale_entries.unit_price as unit_price', 'sale_entries.quantity as ordered_quantity')
                            ->get();                          
        foreach($reports as $report) {
            $quantity = $report->amount_paid;
            $total = $total + $quantity;
            $eArray = [];
            foreach($entries as $entry) {
                if($entry->sale_id == $report->sale_id) {
                   array_push($eArray, $entry);
                }
            }
            $report->entry_data = $eArray;
        }
        $userDet = isset($user) ? $userName->username : 'all';
        $dateDet = isset($date);
        // $adDateFrom = isset($fromDate) ? $fromDate.toDateString() : ''; 
        // $adDateFrom = isset($fromDate) ? $fromDate.toDateString() : '';
        $sales_rep = '';
        $dateDetail = '';
        if(isset($date)) {
            $dateDetail = 'on '.$date; 
        } 
        if(isset($fromDate)) {
            $dateDetail = $dateDetail." from ".$fromDate;
        }
        if(isset($toDate)){
            $dateDetail = $dateDetail." until ".$toDate;
        }
        $detail = 'sales report for'.' '.$userDet.$dateDetail;
        $result = ['reports'=>$reports, 'total'=>$total, 'sales_rep'=>$userDet, 'duration'=>$dateDetail];
        // dd($result);   
        return response()->json($result);
    }
    public function purchaseReport() {
        return view('report.purchase.index');
    }

}
