<?php

Route::group(['namespace' => 'Cost'], function(){

	Route::resource('cost', 'CostController');

});