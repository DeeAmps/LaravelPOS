@extends('layouts.master')

@section('title', '|inventory | categories')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
    <div class="row">
        <div class="col-12 inventory-bar">
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('category.index')}}"><i class="fas fa-chevron-left"></i> Categories</a></li>
                <li class="iventory-menu-item"><a href="{{route('product_type.index')}}">Products</a></li>
                <li class="iventory-menu-item"><a href="{{route('inventory.index')}}">Inventory</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4>{{$category->name}}</h4> <span>Create category</span>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            
        </div>
    </div>
@endsection

@section('script', '/js/category/show.js')