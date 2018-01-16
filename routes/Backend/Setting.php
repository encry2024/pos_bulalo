<?php


Route::group(
	[
		'namespace' => 'Setting'
	], function(){

		Route::resource('setting', 'SettingController');

		Route::resource('setting_table', 'SettingTableController');

});	