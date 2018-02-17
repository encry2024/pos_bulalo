<?php

Route::group(['namespace' => 'Sale'], function(){

	Route::post('sale/save', 'SaleController@save')->name('sale.save');

	Route::post('sale/charge_save', 'SaleController@charge_save')->name('sale.charge_save');

	Route::get('sale/available_table', 'SaleController@available_table')->name('sale.available_table');

	Route::get('sale/unpaid', 'SaleController@unpaid')->name('sale.unpaid');

	Route::get('sale/order/{table}', 'SaleController@get_order')->name('sale.order');

	Route::get('sale/daily', 'SaleController@index')->name('sale.daily');

	Route::get('sale/monthly', 'SaleController@monthly')->name('sale.monthly');

	Route::get('sale/get_order_list/{transaction_no}', 'SaleController@get_order_list')->name('sale.get_order_list');

	Route::post('sale/cancel_order', 'SaleController@cancel_order')->name('sale.cancel_order');

    Route::get('sale/check_cancel_order', 'SaleController@check_cancel_order')->name('sale.cancel_order');

});