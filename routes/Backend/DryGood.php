<?php

Route::group(['prefix' => 'dry_good', 'namespace' => 'DryGood', 'as' => 'dry_good.'], function(){

	Route::group(['namespace' => 'Inventory'], function() {
        Route::post('inventory/get_item', 'InventoryController@getItem')->name('inventory.get_item');

		Route::get('inventory/get', 'InventoryTableController')->name('inventory.get');

		Route::resource('inventory', 'InventoryController', ['except' => ['show']]);

	});


	Route::group(['namespace' => 'Stock'], function(){

		Route::get('stock/get', 'StockTableController')->name('stock.get');

		Route::resource('stock', 'StockController');

	});


	Route::group(['namespace' => 'Dispose'], function(){

		Route::get('dispose/get', 'DisposeTableController')->name('dispose.get');

		Route::resource('dispose', 'DisposeController');

	});


	Route::group(['namespace' => 'GoodsReturn'], function(){

		Route::get('goods_return/get', 'GoodsReturnTableController')->name('goods_return.get');

		Route::resource('goods_return', 'GoodsReturnController');

	});

	Route::group(['namespace' => 'Delivery'], function(){
	    Route::post('delivery/get/item', 'DeliveryController@getItem')->name('delivery.get_item');

		Route::get('delivery/get', 'DeliveryTableController')->name('delivery.get');

		Route::resource('delivery', 'DeliveryController');

	});


	Route::group(['namespace' => 'Invoice'], function(){

		Route::resource('invoice', 'InvoiceController');

	});

	Route::group(['namespace' => 'OrderForm'], function(){

		Route::resource('order_form', 'OrderFormController');

	});

});