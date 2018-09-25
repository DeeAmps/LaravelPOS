@extends('layouts.master')

@section('title', 'pos | cart')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/report.css')

@section('content')
    <div class="row">
        <div class="col-md-12 inventory-bar">
            <div class="page-title"><span>Daily Report @isset($reportHeader){{$reportHeader}}@endisset</span></div>
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('product.index')}}"><i class="fas fa-chevron-left"></i> Reports</a></li>
                <li class="iventory-menu-item"><a href="{{route('report.sale.daily')}}">Daily Report</a></li>
                <li class="iventory-menu-item"><a href="{{route('report.sale.daily')}}">Weekly Report</a></li>
                <li class="iventory-menu-item"><a href="{{route('report.sale.monthly')}}">Monthly Report</a></li>
                <li class="iventory-menu-item"><a href="{{route('report.sale.annual')}}">Annual Report</a></li>
                <li class="iventory-menu-item"><a href="{{route('report.sale.advance')}}">Advanced Report</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 bg-light">
            <form class="mt-5 mb-5">
                <div class="form-group mb-5">
                    <label for="">Sales Rep</label>
                    <select name="" id="sales-rep-filter" class="form-control">
                        @isset($users)
                            @foreach($users as $user)
                               <option value="{{$user->id}}">{{$user->username}}</option> 
                            @endforeach
                        @endisset
                    </select>
                </div>

                <div class="form-group mb-5">
                    <label for="">Date:</label>
                    <input type="text" class="form-control" id="date-filter">
                </div>
                {{csrf_field()}}
                <button class="btn btn-info btn-block" type="button">Generate Report</button>
            </form>
        </div>
        <div class="col-md-9">
            <table class="table">
                <thead>
                    <tr>
                        <th>Reference code</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Seller</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($reports)
                        @foreach($reports as $report)
                            <tr>
                                <td>{{$report->reference_code}}</td>
                                <td>{{$report->created_at->toDateString()}}</td>
                                <td>{{$report->payment->currency->symbol.' '. $report->payment->quantity}}</td>
                                <td>{{$report->creator->name}}</td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@section('script', '/js/inventory.js')
@section('script2', '/js/pos.js')