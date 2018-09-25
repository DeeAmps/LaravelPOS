@extends('layouts.master')

@section('title', 'inventory | stock')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
    <div class="row">
        <div class="col-md-12 inventory-bar">
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('inventory.index')}}"><i class="fas fa-chevron-left"></i> Inventory</a></li>
                <li class="iventory-menu-item"><a href="{{route('product.index')}}">Product</a></li>
                <li class="iventory-menu-item"><a href="{{route('inventory.index')}}">Depots</a></li>
            </ul>
        </div>
    </div>

    <div class="row ml-1 mr-1 mt-2" style="background-color:aliceblue">
        <div class="col-md-4 col-xs-12" style="border-right:1px solid #b0b3b7">
            <div class="row">
                <div class="col-md-12">
                    <form class="product-filter-form mb-4 mt-4" style="width:100%">
                        <div class="form-row align-items-center mb-2" style="width:100%">
                            <div class="col-auto" style="width:100%">
                                <label for="">Depot:</label>
                                <select name="depot_id" id="depot-select" class="form-control"></select>
                            </div>
                        </div>
                        <div class="form-row align-items-center" style="width:100%">
                            <div class="col-auto" style="width:100%">
                                <input type="search" class="form-control" name="filter" placeholder="search product.." style="width:100%" autocomplete="off"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="height:650px; overflow-y:auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Sku</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($products)
                                @foreach($products as $product)
                                    <tr data-product={{$product}} data-sku={{$product->stock_units}}>
                                        <td>{{$product->name}}</td>
                                        <td class="product-id hidden">{{$product->id}}</td>
                                        <td class="product-sku">
                                            <select class="product-sku-select form-control">
                                                @isset($product->stock_units)
                                                    @foreach($product->stock_units as $sku)
                                                        <option value="{{$sku->id}}">{{$sku->label}}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </td>
                                        <td><button class="btn btn-primary add-entry-to-list-btn"><i class="fas fa-dollar-sign"></i></button></td>
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
            
            
        </div>
        <div class="col-md-8 col-xs-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Sku</th>
                        <th>Old price</th>
                        <th>New cost price</th>
                        <th>Profit markup</th>
                        <th>New selling price</th>
                    </tr>
                </thead>
                <tbody class="stock-update-tbody">
                    
                </tbody>
            </table>
        </div>
    </div>
@endsection
<script>
</script>
@section('script', '/js/inventory.js')
@section('script2', '/js/inventory/depot-product-stock.js')