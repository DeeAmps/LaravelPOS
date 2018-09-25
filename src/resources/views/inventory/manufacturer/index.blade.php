@extends('layouts.master')

@section('title', 'inventory | manufacturer')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
    <div class="row">
        <div class="col-12 inventory-bar">
            <div class="page-title"><span>manufacturers</span></div>
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('inventory.index')}}"><i class="fas fa-chevron-left"></i> Inventory</a></li>
                <li class="iventory-menu-item"><a href="{{route('category.index')}}">Categories</a></li>
                <li class="iventory-menu-item"><a href="{{route('product.index')}}">Products</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 col-xs-6">
            <h4>Manufacturers</h4>
        </div>
        <div class="col-md-4 col-xs-6">
            <form class="product-filter-form mb-2">
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <input type="search" class="form-control" placeholder="search manufacturer.." style="width:400px;" name="filter"/>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-xs-12">
            
        </div>
    </div>
    <div class="row main-content">
        <div class="col-md-9 col-xs-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Manufacturer name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Number of products</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($manufacturers)
                        @foreach($manufacturers as $manufacturer)
                            <tr>
                                <td class="hidden manufacturer-list-id">{{$manufacturer->id}}</td>
                                <td>{{$manufacturer->name}}</td>
                                <td>{{$manufacturer->phone}}</td>
                                <td>{{$manufacturer->email}}</td>
                                <td>{{str_limit($manufacturer->address, 30)}}</td>
                                <td>{{$manufacturer->numberOfProducts()}}</td>
                                <td>
                                    {{-- <a href="{{route('manufacturer.show', ['id'=>$manufacturer->id])}}"><button class="btn btn-default product-show-btn"><i class='fas fa-eye'></i></button></a> --}}
                                    <a href="{{route('manufacturer.edit', ['id'=>$manufacturer->id])}}"><button class="btn btn-default product-edit-btn"><i class='fas fa-edit'></i></button></a>
                                    {{-- <button class="btn btn-danger product-delete-btn"><i class='fas fa-trash'></i></button> --}}
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
            <div class="paginator-wrapper">{{ $manufacturers->links() }}</div>
        </div>
        <div class="col-md-3 col-xs-12 right-well">
            <h3>New Manufacturer</h3>
            <form action="{{route('manufacturer.store')}}" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name"/>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" class="form-control" name="phone"/>
                </div>
                <div class="form-group">
                    <label for="name">Email:</label>
                    <input type="text" class="form-control" name="email"/>
                </div>
                <div class="form-group">
                    <label for="manufacturer_id">Address:</label>
                    <textArea class="form-control" name="address" rows="5"></textArea>
                </div>
                {{csrf_field()}}
                <button class="btn btn-info btn-block" type="submit">Create Manufacturer</button>
            </form>
        </div>
    </div>

@endsection

{{-- @section('script', '/js/inventory.js') --}}