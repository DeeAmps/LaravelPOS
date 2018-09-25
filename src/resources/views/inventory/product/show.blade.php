@extends('layouts.master')

@section('title', 'inventory | products')

@section('css_file2', '/css/menubar.css')
@section('css_file', '/css/inventory.css')

@section('content')
    <div class="row">
        <div class="col-md-12 inventory-bar">
            <ul id="inventory-menu-wrapper">
                <li class="iventory-menu-item"><a href="{{route('product.index')}}"><i class="fas fa-chevron-left"></i> Products</a></li>
                <li class="iventory-menu-item"><a href="{{route('inventory.index')}}">Inventory</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <h4>Product detail</h4>
            <input type="text" id="product-detail-id" readonly class="hidden" value="{{$product->id}}"/>
            <div class="row">
                <div class="col-sm-4 detail-label">Name:</div>
                <div class="col-sm-8 detail-value">{{$product->name}}</div>
            </div>
            <div class="row">
                <div class="col-sm-4 detail-label">Barcode:</div>
                <div class="col-sm-8 detail-value">{{$product->barcode}}</div>
            </div>
            <div class="row">
                <div class="col-sm-4 detail-label">Category:</div>
                <div class="col-sm-8 detail-value">{{$product->category->name}}</div>
            </div>
            <div class="row">
                <div class="col-sm-4 detail-label">Description:</div>
                <div class="col-sm-8 detail-value">{{$product->description}}</div>
            </div>
            <div class="row">
                <div class="col-sm-4 detail-label">Manufacturer:</div>
                <div class="col-sm-8 detail-value">{{$product->manufacturer->name}}</div>
            </div>
        </div>

        <div class="col-md-8 col-xs-12" style="border-left:solid #021e47 1px;">
            <div class="row bg-light mx-1">
                <div class="col-md-6 col-xs-12 my-2">
                    <select name="" id="product-sku-select" class="form-control">
                        @isset($stocks)
                            @foreach($stocks as $stock)
                                <option value={{$stock->id}}>{{$stock->label}}</option>
                            @endforeach
                        @endisset($stok_units)
                    </select>
                </div>
                <div class="col-md-3 col-xs-12 my-2">
                    <button class="btn btn-default btn-block" id="open-create-sku-modal">Create New sku</button>
                </div>
                <div class="col-md-3 col-xs-12 my-2">
                    <button class="btn btn-info float-right btn-block" id="attach-sku">Attach to Product</button>
                </div>
            </div>
            <div class="row bg-light mx-1">
                <div class="col-md-12" style="max-height:700px; overflow-y:auto; margin-top:5px;">
                    @if($product->stock_units->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Stock Unit</th>
                                    <th>status</th>
                                    <th>Quntity to default</th>
                                    <th>cost-price</th>
                                    <th>old selling price</th>
                                    <th>New Selling price</th>
                                    
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="product-stock-table-body">
                                @isset($product->stock_units)
                                @foreach($product->stock_units as $stock)
                                    <tr>
                                        <td class="hidden sku-id">{{$stock->id}}</td>
                                        <td>{{$stock->label}}</td>
                                        <td>{{$stock->id==$product->default_stock_unit ? 'default sku' : ''}}</td>
                                        <td>{{$stock->relative_sku_to_sku}}</td>
                                        <td>{{($stock->stock['cost_price']/100)}}</td>
                                        <td>{{($stock->stock['selling_price'])/100}}</td>
                                        <td><input type="number" class="sku-selling-price-entry" class="form-control" style="width:60%" step="0.01"/></td>
                                        <td><button class="btn btn-warning remove-sku">Remove</button></td>
                                        <td><button class="btn btn-info update-selling-price">Update selling price</button></td>
                                    </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="create-sku-modal" tabindex="-1" role="dialog">
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
                            <label for="">LABEL</label>
                            <input type="text" class="form-control" id="create-sku-label" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="">Quantity to sku</label>
                            <input type="text" class="form-control" id="create-sku-quantity">
                        </div>
                    </div>
                    <div class="modal-footer">
                            {{csrf_field()}}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-default" id="create-sku-submit">Create SKU</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('includes.inventory.add-stock-to-product-modal')
@endsection
<script>
        var _product = {!! json_encode($product->toArray()) !!};
</script>
@section('script2', '/js/inventory/product.js')