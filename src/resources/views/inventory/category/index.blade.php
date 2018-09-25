@extends('layouts.master')

@section('title', '|inventory | categories')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
    @include('includes.error')
    <div class="row">
        <div class="col-12 inventory-bar">
            <div class="page-title"><span>Categories</span></div>
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('inventory.index')}}"><i class="fas fa-chevron-left"></i> Inventory</a></li>
                <li class="iventory-menu-item"><a href="{{route('product.index')}}">Products</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9 col-xs-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Number of products</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @isset($categories)
                        @foreach($categories as $category)
                            <tr>
                                <td>{{$category->name}}</td>
                                <td>{{$category->numberOfProducts()}}</td>
                                <td class="hidden category-id" class="hidden">{{$category->id}}</td>
                                <td>
                                    <button class="btn btn-default edit-category-link"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-danger category-delete"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
            <div class="paginator-wrapper">{{ $categories->links() }}</div>
        </div>

        <div class="col-md-3 col-xs-12 right-well">
            <h3>Create category</h3>
            <form action="{{route('category.store')}}" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name">
                </div>
                {{csrf_field()}}
                <button type="submit" class="btn btn-info btn-block">Create category</button>
            </form>
        </div>
    </div>

    @include('includes.inventory.create-category-modal')    
@endsection

@section('script', '/js/inventory.js')