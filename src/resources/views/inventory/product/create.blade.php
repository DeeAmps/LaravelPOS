@extends('layouts.master')

@section('title', 'product-create')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="row">
        <div class="col-12 inventory-bar">
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('inventory.index')}}">Inventory</a></li>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <h4>Create Product</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <form id="create-product-form">
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <h4 class="form-col-headers">Product basic</h4>
                        <div class="form-group">
                            <label for="name">Product Name:</label>
                            <input type="text" name="name" class="form-control" id="product-name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="name">Barcode:</label>
                            <input type="text" name="barcode" class="form-control" id="product-barcode" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Category:</label>
                            <select name="category_id" id="product-category" class="form-control">
                                @isset($categories)
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Product Manufacturer:</label>
                            <select name="manufacturer-id" id="manufacturer-id" class="form-control">
                                @isset($manufacturers)
                                    @foreach($manufacturers as $manufacturer)
                                        <option value={{$manufacturer->id}}>{{$manufacturer->name}}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
        
                        <div class="form-group">
                            <label for="name">Product Description:</label>
                            <textarea name="description" id="product-description" cols="30" rows="5" placeholder="product description" class="form-control"></textarea>
                        </div>
                        
                        <div style="width:100%"><button class="btn btn-success float-right" type="submit">Create Product</button></div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12"><h4 class="form-col-headers">Pricing on stock units</h4></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-default add-new-stock-row" type="button">Add stock unit</button>

                                <table class="table" style="margin-top:15px;">
                                    <thead>
                                        <tr>
                                            <th>Stock Unit</th>
                                            <th>Cost Price</th>
                                            <th>Margin markup</th>
                                            <th>Selling price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="stock-unit-price-tbody">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    {{-- <div class="col-md-2 col-xs-12 offset-md-1">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="form-col-headers">Pricing</h4>
                                <div class="form-group">
                                    <label for="currency_id">Currency:</label>
                                    <select name="currency_id" id="product-category" class="form-control">
                                        @isset($currencies)
                                            @foreach($currencies as $currency)
                                                <option value="{{$currency->id}}">{{$currency->name}}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Cost Price</label>
                                    <input type="text" class="form-control" name="cost_price">
                                </div>
                                <div class="form-group">
                                    <label for="">Profit markup</label>
                                    <input type="text" class="form-control" name="profit_markup" id="product-profit-markup">
                                </div>
                                <div class="form-group">
                                    <label for="">Selling Price</label>
                                    <input type="text" class="form-control" name="selling_price" id="product-selling-price" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 offset-md-1">
                        <h4 class="form-col-headers">Stock Units</h4>
                        <div class="form-group">

                        </div>
                    </div> --}}
                </div>
            </form>   
        </div>
    </div>
@endsection

@section('script', '/js/inventory.js')