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

Route::middleware(['auth'])->group(function(){

	Route::get('/', 'HomeController@index');
	Route::get('settings',['as'=>'settings.edit','uses'=>'SessionsController@edit']);
	Route::post('settings',['as'=>'settings.update','uses'=>'SessionsController@update']);
	Route::get('logout', 'Auth\LoginController@logout');

	Route::get('dashboard','DashboardController@index');

	Route::resource('maintenance/supply','SupplyController');

	Route::resource('maintenance/unit','UnitsController');

	Route::resource('maintenance/reference','ReferenceController');

	Route::resource('maintenance/category','CategoriesController');

	Route::resource('maintenance/product', 'ProductsController');

	Route::resource('purchaseorder','PurchaseOrderController');

	Route::get('request/generate','RequestController@generate');

	Route::get('receipt/{receipt}/print','ReceiptController@printReceipt');

	Route::resource('receipt','ReceiptController');

	Route::get('sale/in', 'SalesController@in');

	Route::get('sale/out', 'SalesController@out');
	Route::post('sale/out', 'SalesController@destroy');
	Route::get('sale/print_index','SalesController@printSale');
	Route::resource('sale', 'SalesController');

	Route::get('stockcard/{id}', 'StockCardController@show');

	Route::get('stockcard/{id}/print', 'StockCardController@printStockCard');
	Route::get('stockcard/print', 'StockCardController@printAllStockCard');

	Route::middleware(['cashier'])->group(function(){

	});

	Route::get('inventory/supply', 'SupplyInventoryController@index');

	Route::middleware(['admin'])->group(function(){
		Route::get('audittrail','AuditTrailController@index');
		Route::resource('account','AccountsController');
		Route::post('account/password/reset','AccountsController@resetPassword');
		Route::put('account/access/update',[
			'as' => 'account.accesslevel.update',
			'uses' => 'AccountsController@changeAccessLevel'
		]);
		Route::get('import','ImportController@index');
		Route::post('import','ImportController@store');
	});

});

Auth::routes();
