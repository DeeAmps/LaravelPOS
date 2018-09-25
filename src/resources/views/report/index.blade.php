@extends('layouts.master')

@section('title', 'pos | cart')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/report.css')

@section('content')
    <div class="row">
        <div class="col-md-12 inventory-bar">
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('report.sale')}}">Sale Report</a></li>
                <li class="iventory-menu-item"><a href="{{route('report.purchase')}}">Purchase Report</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
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
            </table>
        </div>
    </div>
@endsection

@section('script2', '/js/pos.js')