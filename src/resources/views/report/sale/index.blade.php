@extends('layouts.master')

@section('title', 'pos | cart')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/report.css')

@section('content')
    <div class="row">
        <div class="col-md-12 inventory-bar">
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('report.index')}}"><i class="fas fa-chevron-left"></i> Reports</a></li>
                <li class="iventory-menu-item"><a href="{{route('report.purchase')}}">Purcahase Report</a></li>
            </ul>
        </div>
    </div>
    <div class="row main-row mt-3">
        <div class="col-md-2 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-12">
                    
                </div>
            </div>
            <div class="card" style="min-height:750px">
                <div class="card-header bg-info white-text">Filter</div>
                <div class="card-body">
                    <form id="sale-report-filter-form">
                        <div class="form-group">
                            <label for="user">Sales Rep:</label>
                            <select name="user" id="user-filter" class="form-control">
                                <option value="" selected>All</option>
                                @isset($users)
                                    @foreach($users as $user)
                                        <option value={{$user->id}}>{{$user->username}}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="form-group" id="">
                            <label for=""><input type="checkbox" class="form-control-check" id="toggle-filter"/>  Advance Filter</label>
                        </div>
                        <div class="form-group" id="date-wrapper">
                            <label for="">Date</label>
                            <input type="text" class="form-control" id="date-filter" autocomplete="off"/>
                        </div>
                        <div class="hidden" id="advance-search-wrapper">
                            <div class="form-group">
                                <label for="">From Date:</label>
                                <input type="text" class="form-control" name="from_date" id="from-date-filter" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="">To Date:</label>
                                <input type="text" class="form-control" name="to_date" id="to-date-filter" autocomplete="off">
                            </div>
                        </div>
                        {{csrf_field()}}
                        <button class="btn btn-block btn-outline-info" type="submit">Generate Report</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header bg-info white-text">
                    Report For:
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Sales Rep:</label>
                                <input type="text" class="form-control" id="report-detail-user" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Total Amount (GHC)</label>
                                <input type="text" class="form-control report-total-amount-for-period" readonly style="font-weight:bold">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Duration:</label>
                                <input type="text" class="form-control" readonly id="report-detail-duration">
                            </div>
                            <div class="form-group">
                                <label for="">Balance</label>
                                <input type="text" class="form-control" id="report-detail-balance" readonly/>
                            </div>
                        </div>

                    </div>
                    <div class="row" style="height:490px; overflow-y:auto">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr class="grey-text">
                                        <th>Date</th>
                                        <th>Reference code</th>
                                        <th>Seller</th>
                                        <th>Buyer</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="sale-report-tbody"></tbody>
                                <tfoot>
                                    
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
        <div class="col-md-3">
            <div class="card" style="min-height:750px">
                <div class="card-header bg-info white-text">Report Detail:</div>
                <div class="card-body">
                    <div class="product-list-container">
                        <table class="table table-striped">
                            <thead>
                                <tr class="grey-text">
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                </tr>
                            </thead>
                            <tbody id="report-product-tbody">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <span id="report-entries-ref"></span>
                </div>
            </div>
            
        </div>
    </div>
@endsection

@section('script2', '/js/report/sale-report.js')