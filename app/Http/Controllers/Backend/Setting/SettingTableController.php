<?php

namespace App\Http\Controllers\Backend\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting\Table;

class SettingTableController extends Controller
{
    public function index() {
    	$setting = Table::All()->first();

    	return view('backend.setting.table.index', compact('setting'));
    }

    public function update(Table $setting_table, Request $request) {
		
    	$setting_table->count = $request->count;

    	$setting_table->save();

    	return redirect()->route('admin.setting.index');
    }
}
