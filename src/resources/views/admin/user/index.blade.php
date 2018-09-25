@extends('layouts.master')

@section('title', 'inventory | products')


@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
<div class="row">
        <div class="col-12 inventory-bar">
            <div class="page-title"><span>ADMINISTRATION</span></div>
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
                            <a class="dropdown-item" href="{{route('product.depot-stock-index')}}">Stock Update</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    @include('includes.error')
    
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Sales-Rep</th>
                        <th>Manager</th>
                        <th>Admin</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @isset($users)
                        @foreach($users as $user)
                            <tr>
                                <form action={{route('admin.assign.role', ['user'=>$user->id])}} method="post">
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->username}}</td>
                                    <td>{{$user->email}}</td>
                                    <td><input type="checkbox" name="sales-rep" {{$user->hasRole('sales-rep')?'checked':''}}></td>
                                    <td><input type="checkbox" name="manager" {{$user->hasRole('manager')?'checked':''}}></td>
                                    <td><input type="checkbox" name="admin" {{$user->hasRole('admin')?'checked':''}}></td>
                                    {{csrf_field()}}
                                    <td><button class="btn btn-default" type="submit">Assign Role</button></td>
                                </form>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script', '/js/inventory.js')