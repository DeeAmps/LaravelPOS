@extends('layouts.master')

@section('title', 'inventory')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
    <script>
        var products = {!! json_encode($products->toArray()) !!};
    </script>
    <div class="row">
        <div class="col-12 inventory-bar">
            <div class="page-title"><span>INVENTORY</span></div>
            <ul id="inventory-menu-wrapper">
                <li class='inventory-menu-item'>
                    <div class="dropdown">
                        <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Products <i class="fas fa-angle-down"></i>
                        </span>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{route('product.index')}}">Products</a>
                            <a class="dropdown-item" href="{{route('manufacturer.index')}}">Manufacturers</a>
                            <a class="dropdown-item" href="{{route('category.index')}}">View All Categories</a>
                        </div>
                    </div>
                </li>
                <li class='inventory-menu-item'>
                    <div class="dropdown">
                        <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Product Stocking <i class="fas fa-angle-down"></i>
                        </span>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            {{-- <a class="dropdown-item create-product-link" href="#">Create Product</a> --}}
                            <a class="dropdown-item" href="{{route('stock.adjust.index')}}">Stock Adjust</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        
    </div>
    <div class="row mt-2">
       <div class="col-md-8">
            <div class="card" style="min-height:750px">
                <div class="card-header bg-info white-text">
                Inventory of All Products
                </div>
                <div class="card-body">
                    <div style="height:600px; overflow-y:auto">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="grey-text">
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Stock Quantity(Piece)</th>
                                    <th>Cost Price(Piece)</th>
                                    <th>Selling Price(Piece)</th>
                                </tr>
                            </thead>
                            <tbody id="inventory-products-tbody"></tbody>
                        </table>
                        <div class="paginator-wrapper">{{ $products->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
       <div class="col-md-4">
            <div class="card" style="min-height:750px">
                <div class="card-header bg-info white-text">
                Product-Detail: <span id="detail-product-name"></span>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr class="grey-text">
                                <th>sku</th>
                                <th>stock quantity</th>
                                <th>cost price</th>
                                <th>selling price</th>
                            </tr>
                        </thead>
                        <tbody id="inventory-detail-tbody">

                        </tbody>
                    </table>
                </div>
            </div>
       </div>
    </div>

    @include('includes.inventory.create-category-modal')
    @include('includes.inventory.create-product-modal')
@endsection

@section('script', '/js/inventory.js')