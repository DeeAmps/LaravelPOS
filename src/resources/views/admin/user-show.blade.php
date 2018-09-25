@extends('layouts.master')

@section('title', 'admin | user')


@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/admin.css')

@section('content')
    <div class="row">
        <div class="col-12 inventory-bar">
            <div class="page-title"><span>User profile</span></div>
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('admin.users')}}"><i class="fas fa-chevron-left"></i> Users</a></li>
            </ul>
        </div>
    </div>
    @include('includes.error')
    
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header bg-info white-text">{{$user->name}}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4" style="border-right:solid 1px #c7c8c9">
                            <div class="form-group mb-5">
                                <label for="">Name:</label>
                                <input type="text" class="form-control" readonly value={{$user->name}}>
                            </div>
                            <div class="form-group mb-5">
                                <label for="">Username:</label>
                                <input type="text" class="form-control" readonly value={{$user->username}}>
                            </div>
                            <div class="form-group mb-5">
                                <label for="">Email:</label>
                                <input type="text" class="form-control" readonly value={{$user->email}}>
                            </div>
                            <div class="form-group mb-5">
                                <label for="">Registered On:</label>
                                <input type="text" class="form-control" readonly value={{$user->created_at}}>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="grey-text">
                                        <th>Sales-Rep</th>
                                        <th>Manager</th>
                                        <th>Admin</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <form action={{route('admin.assign.role', ['user'=>$user->id])}} method="post">
                                            <td><input type="checkbox" name="sales-rep" {{$user->hasRole('sales-rep')?'checked':''}}></td>
                                            <td><input type="checkbox" name="manager" {{$user->hasRole('manager')?'checked':''}}></td>
                                            <td><input type="checkbox" name="admin" {{$user->hasRole('admin')?'checked':''}}></td>
                                            {{csrf_field()}}
                                            <td><button class="btn btn-default" type="submit">Assign Role</button></td>
                                        </form>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script', '/js/inventory.js')