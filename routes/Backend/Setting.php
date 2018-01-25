<?php


Route::group(
	[
		'namespace' => 'Setting'
	], function(){

		Route::resource('setting', 'SettingController');

		Route::get('setting_table/get', 'SettingGetTableController')->name('setting_table.get');

		Route::resource('setting_table', 'SettingTableController');

});	