@extends('layouts.master')

@section('title', 'pos | cart')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/purchase.css')

@section('content')
    <div class="row">
        <div class="col-md-12 inventory-bar">
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('inventory.index')}}"><i class="fas fa-chevron-left"></i> Inventory</a></li>
            </ul>
        </div>
    </div>
    <div class="row main-row">
        <div class="col-md-3">
            <div class="row pb-3 mb-2">
                <div class="col-12">
                    <div class="card mb-3" style="width: 100%; min-height:750px">
                        <div class="card-header bg-info">Product Search:</div>
                        <div class="card-body">
                            <form class="form" id="product-search-form">
                                <div class="form-row">
                                    <input type="search" class="form-control" autofocus id="product-search-input" placeholder="search" autocomplete="off"/>
                                    {{csrf_field()}}
                                    <button class="btn btn-default btn-block mt-1 hidden" type="button"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                            <div style="height:600px; overflow-y:auto">
                                <table class="table">
                                    <thead style="color:#d4d7db">
                                        <tr>
                                            <th>Product</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-search-result-tbody">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3" style="width:100%; min-height:750px">
                        <div class="card-header bg-info"><i class="fas fa-bars"></i> Vendor Detail:</div>
                        <div class="card-body">
                            <div class="row mb-3" style="background-color:#dcdfe2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Vendor</label>
                                        <select name="" id="purchase-vendor-select" class="form-control">
                                            @isset($customers)
                                                @foreach($customers as $customer)
                                                    <option value={{$customer->id}}>{{$customer->label}}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Invoice code:</label>
                                        <input type="text" class="form-control" id="invoice-ref-code-input" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">TOTAL:</label>
                                        <input type="text" class="form-control" id="purchase-total-amount-value" readonly/>
                                    </div>
                                    <div class="form-group mt-5">
                                        <button class="btn btn-block btn-outline-dark" id="create-purchase-btn">Create Purchase</button>
                                    </div>
                                </div>
                            </div>
                            <div style="height:450px; overflow-y:auto" class="bg-light">
                                <table class="table table-bordered">
                                    <thead style="color:#bec2c6">
                                        <tr>
                                            <th>Product</th>
                                            <th>Stock Unit</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Amount</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id='purchase-entries-tbody'>
                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
@endsection

@section('script2', '/js/purchase.js')