<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->middleware('auth')->name('home');


Route::resource('/user', 'UserController')->names([
    'read' =>'user.list',
    'create' => 'user.create',
    'update'=> 'user.update',
    'delete'=> 'user.delete'
]);

Route::get('/product-search', 'ProductSearchController@search');

Route::group(['middleware' => ['eroles:admin,manager,sales-rep']], function () {
    Route::get('/pos', 'PosController@index')->name('pos.index');
    Route::get('/pos/product/search', 'PosController@search')->name('pos.search');
    Route::post('/pos/sales/close-order', 'PosController@closeSaleOrder')->name('pos.complete.sale');
});


Route::group(['middleware' => ['eroles:admin']], function () {
    Route::prefix('admin')->group(function(){
        Route::get('/users', 'AdminController@index')->name('admin.users');
        Route::get('/users/{id}', 'AdminController@getUser');
        Route::delete('/users/{id}', 'AdminController@deleteUser')->name('admin.user.delete');
        Route::post('/users/{user}/assign-role', 'AdminController@assignRole')->name('admin.assign.role');

        Route::get('/backup', 'AdminController@backupIndex')->name('admin.backup.index');
        Route::get('/backup/create', 'AdminController@createBackup')->name('admin.backup.create');
        Route::post('/loadcsv', 'AdminController@loadcsv')->name('load.csv');
        Route::get('/getloadcsv', 'AdminController@getcsv')->name('admin.csv');
    });
});


Route::group(['middleware' => ['eroles:admin,manager']], function () {
    Route::resource('/user', 'UserController')->names([
        'read' =>'user.list',
        'create' => 'user.create',
        'update'=> 'user.update',
        'delete'=> 'user.delete'
    ]);
    

    Route::resource('/customer', 'CustomerController')->names([
        'read' =>'customer.list',
        'create' => 'customer.create',
        'update'=> 'customer.update',
        'delete'=> 'customer.delete'
    ]);

    Route::get('/report', 'ReportController@index')->name('report.index')->middleware('eroles:admin,manager');
    Route::get('/report/sale', 'ReportController@saleReport')->name('report.sale');
    Route::get('/report/sale/search', 'ReportController@searchSale')->name('report.sale.search');
    Route::get('/report/purchase', 'ReportController@purchaseReport')->name('report.purchase');
    Route::get('/report/sale/daily', 'ReportController@getDailySale')->name('report.sale.daily');
    Route::get('/report/sale/weekly', 'ReportController@getWeeklySale')->name('report.sale.weekly');
    Route::get('/report/sale/monthly', 'ReportController@getMonthlySale')->name('report.sale.monthly');
    Route::get('/report/sale/annual', 'ReportController@getAnnualSale')->name('report.sale.annual');
    Route::get('/report/sale/advanced', 'ReportController@getAdvancedSale')->name('report.sale.advance');
    

    Route::resource('/purchase', 'PurchaseController')->names([
        'read' =>'purchase.list',
        'create' => 'purchase.create',
        'update'=> 'purchase.update',
        'delete'=> 'purchase.delete'
    ]);


    Route::prefix('inventory')->group(function(){
        Route::get('/', 'InventoryController@index')->name('inventory.index');
        Route::put('/product/{product}/stock-unit/{stockunit}/update-stock-quantity', 'InventoryController@updateStockQuantity')->name('inventory.update.quantity');
        Route::get('/product/{productId}/stock-units/add', 'InventoryController@addStockUnitToProduct')->name('inventory.add-stock-to-product');
        Route::get('/product/{productId}/stock-units/{stockUnitId}/remove', 'InventoryController@removeStockUnitFromProduct')->name('inventory.remove-stock-from-product');
        Route::get('/product/search', 'InventoryController@searchProduct')->name('inventory_product.search');
        Route::post('/product/{product}/stock-units', 'ProductStockUnitController@index')->name('product_stockunit.index');
        Route::post('/product/{product}/stock-units/create', 'ProductStockUnitController@create')->name('product_stockunit.create');
        Route::post('/product/{product}/stock-units/except', 'ProductStockUnitController@exceptIndex')->name('product_stockunit.exceptIndex');
        Route::get('/product-stock-price-index', 'InventoryController@productStockPriceIndex')->name('product.depot-stock-index');
        Route::get('/product/{productId}/stock-units/{stockUnitId}', 'InventoryController@getStockUnitForProduct');
        Route::put('/product/{productId}/stock-units/{skuId}/update-selling-price', 'InventoryController@updateStockPrice');
        Route::get('/stock/adjustment', 'InventoryController@indexStockAdjustment')->name('stock.adjust.index');
        Route::post('/stock/adjustment', 'InventoryController@postAdjustStock')->name('stock.adjust.create');
        Route::get('/search-product-json', 'InventoryController@searchProductJson')->name('search.product.json');
        Route::post('adjust-stock/reason', 'InventoryController@createAdjustStockReason')->name('create-reason');

        Route::resource('/stock-units', 'StockUnitController')->names([
            'read'=>'stockunit.list',
            'create'=>'stockunit.create',
            'update'=>'stockunit.update',
            'delete'=>'stockunit.delete'
        ]);
        Route::resource('/manufacturer', 'ManufacturerController')->names([
            'read' =>'manufacturer.list',
            'create' => 'manufacturer.create',
            'update'=> 'manufacturer.update',
            'delete'=> 'manufacturer.delete'
        ]);

        Route::resource('/stock-unit', 'StockUnitController')->names([
            'read' =>'sku.list',
            'create' => 'sku.create',
            'update'=> 'sku.update',
            'delete'=> 'sku.delete'
        ]);

        Route::resource('/depot', 'DepotController')->names([
            'read' =>'depot.list',
            'create' => 'depot.create',
            'update'=> 'depot.update',
            'delete'=> 'depot.delete'
        ]);
    
        Route::resource('/category', 'CategoryController')->names([
            'read' =>'category.list',
            'create' => 'category.create',
            'update'=> 'category.update',
            'delete'=> 'category.delete',
            'store'=> 'category.store'
        ]);
    
        Route::resource('/product', 'ProductController')->names([
            'read' =>'product.list',
            'create' => 'product.create',
            'update'=> 'product.update',
            'delete'=> 'product.delete'
        ]);

        Route::get('/inventory-product-search', 'InventoryController@inventorySearch')->name('inventory.search.product');
    });
});

Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );
Auth::routes();


//Route::get('/home', 'HomeController@index')->name('home');
