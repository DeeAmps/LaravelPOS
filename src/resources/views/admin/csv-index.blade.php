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
            </ul>
        </div>
    </div>
    <div class="row" style="padding-top:20px">
        <div class="col-md-12">
            @if ( Session::has('success') )
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>{{ Session::get('success') }}</strong>
                </div>
            @endif
            @if ( Session::has('error') )
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>{{ Session::get('error') }}</strong>
                </div>
            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <div>
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header bg-info white-text"><i class="fas fa-users"></i> Upload csv</div>
                <div class="card-body">
                    <form action="{{ route('load.csv') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        Choose your xls/csv File : <input type="file" name="file" class="form-control">
                        
                        <input type="submit" class="btn btn-primary btn-lg" style="margin-top: 3%">
                    </form>
                </div>
            </div>
        </div>
    </div>    
@endsection

@section('script', '/js/admin/csv.js')