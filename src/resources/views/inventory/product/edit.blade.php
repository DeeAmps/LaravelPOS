@extends('layouts.master')

@section('title', 'inventory | products')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
    <div class="row">
        <div class="col-12 inventory-bar">
            <div class="page-title"><span>Products</span></div>
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('product.index')}}"><i class="fas fa-chevron-left"></i> Products</a></li>
            </ul>
        </div>
    </div>
    @include('includes.error')
    <div class="row">
        <div class="col-md-6 offset-3">
            <h3>Edit Product</h3>
            <form method='POST' action="{{route('product.update', ['id'=>$product->id])}}">
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select name="category_id" id="" class="form-control">
                            @isset($categories)
                            @foreach($categories as $category)
                                @if($category->id == $product->category_id)
                                    <option value="{{$category->id}}" selected={{$category->id}}>{{$category->name}}</option>
                                @else
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endif
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" value="{{$product->name}}"/>
                </div>
                <div class="form-group">
                    <label for="">Barcode:</label>
                    <input type="text" class="form-control" name="barcode", value="{{$product->barcode}}">
                </div>
                <div class="form-group">
                    <label for="category">Manufacturer:</label>
                    <select name="manufacturer_id" id="" class="form-control">
                        @isset($manufacturers)
                            @foreach($manufacturers as $manufacturer)
                                @if($manufacturer->id == $product->manufacturer_id)
                                    <option value="{{$manufacturer->id}}" selected={{$manufacturer->id}}>{{$manufacturer->name}}</option>
                                @else
                                    <option value="{{$manufacturer->id}}">{{$manufacturer->name}}</option>
                                @endif
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Description:</label>
                    <textarea name="description" id="" rows="5" class="form-control">{{$product->description}}</textarea>
                </div>
                {{csrf_field()}}
                {{method_field('patch')}}
                <button class="btn btn-warning btn-block" type="submit">update product</button>
            </form>
        </div>
    </div>

@endsection

@section('script', '/js/inventory/product.js')