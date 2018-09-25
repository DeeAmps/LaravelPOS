@extends('layouts.master')

@section('title', 'inventory | manufacturer')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
    <div class="row">
        <div class="col-md-6 offset-3" style="margin-top:10px">
            <div class="card">
                <div class="card-header bg-info white-text">Edit Manufacturer</div>
                <div class="card-body">
                    <form method="POST" action="{{action('ManufacturerController@update', ['id'=>$manufacturer->id])}}">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" name="name" value="{{$manufacturer->name}}">
                        </div>
                        <div class="form-group">
                            <label for="name">Phone::</label>
                            <input type="text" class="form-control" name="phone" value="{{$manufacturer->phone}}">
                        </div>
                        <div class="form-group">
                            <label for="name">Email:</label>
                            <input type="text" class="form-control" name="email" value="{{$manufacturer->email}}">
                        </div>
                        <div class="form-group">
                            <label for="name">Address:</label>
                            <textArea class="form-control" name="address" rows="5">{{$manufacturer->address}}</textArea>
                        </div>
                        {{csrf_field()}}
                        {{ method_field('PATCH') }}
                        <button class="btn btn-warning btn-block" type="submit">Update Manufacturer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script', '/js/inventory.js')