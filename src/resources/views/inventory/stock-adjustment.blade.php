@extends('layouts.master')

@section('title', 'inventory')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
    <div class="row">
        <div class="col-12 inventory-bar">
            <div class="page-title"><span>STOCK ADJUSTMENT</span></div>
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('inventory.index')}}"><i class="fas fa-chevron-left"></i> Inventory</a></li>
            </ul>
        </div>
    </div>

    <div class="row pt-3">
        <div class="col-md-3 col-xs-12">
            <div class="card" style="min-height:750px">
                <div class="card-header bg-info white-text">Search</div>
                    
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="adjust-search-product-form" style="margin:0; padding:0">
                                <input type="search" class="form-control" id="product-search-input" autocomplete="off" placeholder="search">
                                {{csrf_field()}}
                                <button class="btn hidden" type="submit">Search</button>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12" style="height:600px; overflow-y:auto">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="grey-text">
                                        <th>Product Name:</th>
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
        <div class="col-md-9 col-xs-12">
            <div class="card" style="min-height:750px">
                <div class="card-header bg-info white-text">Adjustment</div>
                <div class="card-body">
                    <div class="adjustment-detail row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Reference-code</label>
                                <input type="text" readonly id="adjustment-ref" class="form-control" value={{$historyRef}}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Description  <span id="create-reason" style="color:blue; cursor:pointer">Create new</span></label>
                                <select id="adjustment-description" class="form-control">
                                    @isset($reasons)
                                        @foreach($reasons as $reason)
                                            <option value={{$reason->id}}>{{$reason->label}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Balance</label>
                                <input type="text" class="form-control" id="adjust-balance" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-outline-warning btn-block" id="adjust-btn">Adjust</button>
                        </div>
                    </div>
                    
                    <table class="table mt-3 table-bordered">
                        <thead>
                            <tr class="grey-text">
                                <th>Product</th>
                                <th>Old quantity (pieces)</th>
                                <th>New Quantity (pieces)</th>
                                <th>Difference</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody id="adjustment-tbody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal create-reason-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Reason to adjust</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="create-reason-form">
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Label</label>
                    <input type="text" class="form-control" id="create-reason-name" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <input type="text" class="form-control" id="create-reason-description" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-default" id="create-reason-submit">Create Category</button>
            </div>
            {{csrf_field()}}
        </form>
            </div>
        </div>
    </div>

    @include('includes.inventory.create-category-modal')
    @include('includes.inventory.create-product-modal')
@endsection

@section('script', '/js/stock-adjust.js')