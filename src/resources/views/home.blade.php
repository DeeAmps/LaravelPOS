@extends('layouts.master')
@section('css_file2', '/css/menubar.css')
@section('content')
<div class="container">
    <div class="row justify-content-center" style="margin-top:50px">
        <div class="col-md-10 mt-4">
            <div class="card">
                <div class="card-header bg-info white-text"><i class="far fa-user"></i> User Detail:</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 profile-text"><span>Name:</span></div>
                        <div class="col-md-8">
                            <span class="profile-text">{{$user->name}}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 profile-text">Username:</div>
                        <div class="col-md-8">
                            <span class="profile-text">{{$user->username}}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 profile-text">Email:</div>
                        <div class="col-md-8">
                            <span class="profile-text">{{$user->email}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 profile-text">Roles:</div>
                        <div class="col-md-8">
                            <ul style="dispaly:flex; flex-direction:column">
                                @isset($user->roles)
                                    @foreach($user->roles as $role)
                                        <li class="profile-text">-{{$role->name}}</li>
                                    @endforeach
                                @endisset
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
