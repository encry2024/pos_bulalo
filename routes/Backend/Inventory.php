<?php

Route::group(['namespace' => 'Inventory'], function(){

	Route::get('pos/inventory/get', 'InventoryTableController')->name('inventory.get');

	Route::get('pos/inventory/get_unit/{id}/{supplier}', 'InventoryController@getUnit')->name('inventory.get_unit');

	Route::resource('pos/inventory', 'InventoryController');

});

