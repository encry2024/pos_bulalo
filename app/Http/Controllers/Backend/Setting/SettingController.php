<?php

namespace App\Http\Controllers\Backend\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting\Setting;

class SettingController extends Controller
{
	public function index(){
		$settings = Setting::all();

		return view('backend.setting.index', compact('settings'));
	}    

	public function edit(Setting $setting){

		return view('backend.setting.edit', compact("setting"));
	}

	public function update(Setting $setting, Request $request){
		$setting->update($request->all());

		return redirect()->route('admin.setting.index')->withFlashSuccess('Setting has been updated!');
	}
}
