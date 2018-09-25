@extends('layouts.master')

@section('title', 'pos | cart')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/pos.css')

@section('content')
    <div class="row menubar">
        <div class="col-md-12 inventory-bar">
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('product.index')}}"><i class="fas fa-chevron-left"></i> Products</a></li>
                <li class="iventory-menu-item"><a href="{{route('inventory.index')}}">Inventory</a></li>
            </ul>
        </div>
    </div>
    <div class="row main-page">
        <div class="col-md-3 col-sm-12 col-xs-12">
            <div class="card" style="">
                <div class="card-header bg-info white-text">Search Product:</div>
                <div class="card-body">
                    <form class="form" id="product-search-form">
                        <input type="search" class="form-control" id="pos-product-search-field" autocomplete="off" placeholder="search">
                        {{csrf_field()}}
                        <button type="submit" class="hidden">submit</button>
                    </form>
                    <div class="table-container" style="height:700px; overflow-y:auto">
                        <table class="table table-striped">
                            <thead>
                                <tr class="grey-text">
                                    <th>Product name:</th>
                                </tr>
                            </thead>
                            <tbody id="pos-product-search-result-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header bg-info white-text">Sales</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">SUBTOTAL:</label>
                            <input type="text" class="form-control" id="sub-total-value" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="">VAT:</label>
                            <input type="text" class="form-control" id="vat-value" readonly value = 0>
                        </div>
                        <div class="col-md-4">
                            <label for="">TOTAL AMOUNT:</label>
                            <input type="text" class="form-control" id="total-value" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12" style="height:650px; overflow-y:auto">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Stock Unit</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Sub-Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="order-table-body">
        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="row">
                <div class="col-md-12">
                    <h5 style="background-color:antiquewhite">OrderReference: <span id="order-ref">{{$orderRef}}</span></h5>
                </div>
            </div>
            <form id="order-checkout-form">
                <div class="form-group">
                    <label for="formControlRange">Adjust VAT</label>
                    {{-- <input type="range"  step="0.01" max="100" value="3" class="form-control-range" id="vat-range"> --}}
                    <input type="number" max="100" value="0" min="0" class="form-control" id="vat-range" disabled>
                </div>
                <div class="form-group">
                    <label for="">Payment:</label>
                    <input type="number" class="form-control" step="0.01" id="amount-paid"/>
                </div>
                <div class="form-group">
                    <label for="">Change</label>
                    <input type="number" class="form-control" readonly id="payment-change"/>
                </div>
                <button class="btn btn-info btn-block mt-5" type="submit" disabled id="sell-btn">Sell</button>
                
            </form>
        </div>
    </div>

    <div class="row receipt-wrapper">
        <div class="col-12">
            <div class="row">
                <div class="col-md-6 col-xs-12 col-sm-12 receipt-header">
                    <span class="receipt-header-item">GIFRIS Electricals</span>
                    <span class="receipt-header-item">KASOA MARKET</span>
                    <span class="receipt-header-item">phone: 0383****</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 receipt-info">
                    <span class="receipt-info-item" id="receipt-ref"></span>
                    <span class="receipt-info-item" id="receipt-date"></span>
                </div>
            </div>
            <div class="row" style="width:50px;">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="receipt-tbody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 receipt-footer">
                    {{-- <span class="receipt-footer-item">SUB-TOTAL: <span id="receipt-sub-total"></span></span>
                    <span class="receipt-footer-item">VAT: <span id="receipt-vat-value"></span></span> --}}
                    
                    <span class="receipt-footer-item">TOTAL: <span id="receipt-total-value"></span></span>
                    <span class="receipt-footer-item">PAID: <span id="receipt-paid-value"></span></span>
                    <span class="receipt-footer-item">CHANGE: <span id="receipt-change-value"></span></span>
                    
                    {{-- <span class="receipt-footer-item">PAID: <span id="receipt-amount-value"></span></span>
                    <span class="receipt-footer-item">Change: <span id="receipt-change-value"></span></span> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script2', '/js/pos.js')