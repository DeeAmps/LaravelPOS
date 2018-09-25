@extends('layouts.master')

@section('title', 'Admin | backup')
@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/admin.css')

@section('content')
    <div class="row pt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info white-text"><i class="fas fa-cogs"></i></div>
                <div class="card-body">
                    {{-- <button class="btn btn-default get-backup">Get</button> --}}
                    <form action="{{route('load.csv')}}" method="post">
                            <div class="form-group">
                                <label for="exampleFormControlFile1">Example file input</label>
                                <input type="file" class="form-control-file" id="exampleFormControlFile1" name="csv_file">
                            </div>
                            {{csrf_field()}}
                            <button class="btn btn-success" type="submit">upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script', '/js/admin/backup.js')