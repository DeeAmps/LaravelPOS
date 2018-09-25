@extends('layouts.master')

@section('title', 'Admin | index')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/admin.css')

@section('content')
    <div class="row">
        <div class="col-12 inventory-bar">
            <div class="page-title"><span>Products</span></div>
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('admin.users')}}">Users</a></li>
                <li class="iventory-menu-item"><a href="{{route('admin.backup.index')}}">Backup and Restore</a></li>
                <li class="iventory-menu-item"><a href="{{route('admin.csv')}}">csv upload</a></li>
            </ul>
        </div>
    </div>
    <div class="row" style="padding-top:20px">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info white-text"><i class="fas fa-users"></i> Users list</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="grey-text">
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($users)
                                @foreach($users as $user)
                                    <tr>
                                        <td class="hidden user-id">{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->username}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>
                                            <ul style="display:flex; flex-direction:row; justify-content:flex-start">
                                                @isset($user->roles)
                                                    @foreach($user->roles as $role)
                                                        <li class="mr-2">-{{$role->name}}</li>
                                                    @endforeach
                                                @endisset
                                            </ul>
                                        </td>
                                        <td><button class="btn btn-default user-entry-open mr-1">View</button><button class="btn btn-danger user-entry-delete">Delete</button></td>
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>    
@endsection

@section('script', '/js/admin/users.js')