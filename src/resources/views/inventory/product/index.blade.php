@extends('layouts.master')

@section('title', 'inventory | products')


@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
    @include('includes.error')
    <div class="row">
        <div class="col-12 inventory-bar">
            <div class="page-title"><span>Products</span></div>
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('inventory.index')}}"><i class="fas fa-chevron-left"></i> Inventory</a></li>
                <li class="iventory-menu-item"><a href="{{route('category.index')}}">Categories</a></li>
                <li class="iventory-menu-item"><a href="{{route('manufacturer.index')}}">Manufacturers</a></li>
            </ul>
        </div>
    </div>
    
    <div class="row main-content">
        <div class="col-md-8 col-xs-12">
            <div class="card">
                <div class="card-header bg-info white-text">Products List</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="product-filter-form mb-2" action="{{route('product.index')}}" style="width:100%">
                                <input type="search" class="form-control" name="filter" placeholder="search product.." style="width:100%;" autocomplete="off"/>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Product Category</th>
                                        <th>Product Manufacturer</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($products)
                                        @foreach($products as $product)
                                            <tr>
                                                <td class="hidden product-list-id">{{$product->id}}</td>
                                                <td>{{$product->name}}</td>
                                                <td>{{$product->category->name}}</td>
                                                <td>{{str_limit($product->manufacturer->name, 30)}}</td>
                                                <td>
                                                    <a href="{{route('product.show', ['id'=>$product->id])}}"><button class="btn btn-default product-show-btn"><i class='fas fa-eye'></i></button></a>
                                                    <a href="{{route('product.edit', ['id'=>$product->id])}}">
                                                        <button class="btn btn-default product-edit-btn"><i class='fas fa-edit'></i></button>
                                                    </a>
                                                    <button class="btn btn-danger product-delete-btn"><i class='fas fa-trash'></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            <div class="paginator-wrapper">{{ $products->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="card" style="min-height:826px">
                <div class="card-header bg-info white-text">Create new product:</div>
                <div class="card-body">
                    <form id="create-product-form">
                        <div class="form-group">
                            <label for="category-id">
                                Category: 
                                <span class="create-category-open-modal ml-3" style="color:blue; cursor:pointer">
                                    Create New
                                </span>
                            </label>
                            <select name="category_id" id="category-select"  class="form-control">
                                @isset($categories)
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                @endisset    
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Brand Name:</label>
                            <input type="text" class="form-control" name="name" id="brand-input" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label for="">Model:</label>
                            <input type="text" class="form-control" id="model-input" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label for="">Size / Spec</label>
                            <input type="text" class="form-control" id="size-input" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label for="name">Barcode:</label>
                            <input type="text" class="form-control" name="barcode" id="barcode-input" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label for="manufacturer_id">
                                Manufacturer:
                                <span class="create-manufacturer-open-modal ml-3" style="color:blue; cursor:pointer">
                                    Create New
                                </span>
                            </label>
                            <select name="manufacturer_id" id="manufacturer-select" class="form-control">
                                @isset($manufacturers)
                                    @foreach($manufacturers as $manufacturer)
                                        <option value="{{$manufacturer->id}}">{{$manufacturer->name}}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textArea class="form-control" name="description" rows="2" id="description-textArea"></textarea>
                        </div>
                        {{csrf_field()}}
                        <button class="btn btn-info btn-block" type="submit">Create Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal create-category-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="create-category-form">
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Category Name</label>
                    <input type="text" class="form-control" id="create-category-name" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-default" id="create-category-submit">Create Category</button>
            </div>
            {{csrf_field()}}
        </form>
            </div>
        </div>
    </div>



    <div class="modal create-manufacturer-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Manufacturer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="create-manufacturer-form">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" class="form-control" id="create-manufacturer-name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="">Phone</label>
                            <input type="text" class="form-control" id="create-manufacturer-phone" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" class="form-control" id="create-manufacturer-email" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                            <textArea class="form-control" id="create-manufacturer-address" rows="5"></textArea>
                        </div> 
                    </div>
                    <div class="modal-footer">
                            {{csrf_field()}}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-default" id="create-manufacturer-submit">Create Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('includes.inventory.create-category-modal')
    @include('includes.inventory.create-manufacturer-modal')
@endsection

@section('script', '/js/inventory/product.js')