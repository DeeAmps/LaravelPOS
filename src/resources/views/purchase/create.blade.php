@extends('layouts.master')

@section('title', 'pos | cart')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/purchase.css')
      

@section('content')
    <div class="row">
        <div class="col-md-12 inventory-bar">
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('report.index')}}"><i class="fas fa-chevron-left"></i> Reports</a></li>
                <li class="iventory-menu-item"><a href="{{route('report.purchase')}}">Purcahase Orders</a></li>
                <li class="iventory-menu-item"><a href="{{route('purchase.order.create')}}">Create Purchase Order</a></li>
            </ul>
        </div>
    </div>  
    <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12" style="border-right: 2px solid #d2d5d8">
            <div class="row">
                <div class="col-12">
                    <form id="product-search-form">
                        <div class="form-group">
                            <label for="">Search</label>
                            <input type="text" class="form-control" id="product-search-field"/>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                    </table>
                    <tbody>

                    </tbody>
                </div>
            </div>
            
        </div>
        <div class="col-md-8 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">
                            Vendor     
                            <span class="create-vendor-open-modal ml-3" style="color:blue; cursor:pointer">
                                Create New
                            </span>
                        </label>
                        <select name="" id="vendor-id" class="form-control">
                            @isset($customers)
                                @foreach($customers as $customer)
                                    <option value={{$customer->id}}>{{$customer->name}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>sku</th>
                                <th>Quantity</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> 




    <div class="modal create-vendor-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Vendor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="create-vendor-form">
                    <div class="modal-body">
                            <div class="form-group">
                                <label for="">Vendor Name:</label>
                                <input type="text" class="form-control" id="vendor-name"/>
                            </div>
                            <div class="form-group">
                                <label for="">Vendor Phone:</label>
                                <input type="text" class="form-control" id="vendor-phone"/>
                            </div>
                            <div class="form-group">
                                <label for="">Vendor Address:</label>
                                <input type="text" class="form-control" id="vendor-address"/>
                            </div>
                            {{csrf_field()}}
                            
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-block btn-default" type="submit">Create Vendor</button>
                    </div>
                </form>
                
            </div>
        </div>
        </div>
@endsection

@section('script', '/js/purchase.js')
@section('script2', '/js/report/sale-report.js')