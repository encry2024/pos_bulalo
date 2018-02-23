<?php

namespace App\Http\Controllers\Backend\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting\Table;
use App\Http\Requests\Backend\Setting\TableRequest;
use App\Http\Requests\Backend\Setting\TableUpdateRequest;

class SettingTableController extends Controller
{
    public function index()
    {
    	return view('backend.setting.table.index', compact('setting'));
    }

    public function create()
    {
        return view('backend.setting.table.create');
    }

    public function store(TableRequest $request)
    {
        $table = new Table();
        $table->number = $request->number;
        $table->price = $request->price;
        $table->description = $request->description;

        if($table->save()) {
            return redirect()->route('admin.setting_table.index')->withFlashSuccess('New table has been created!');
        }
    }

    public function edit(Table $setting_table){
        return view('backend.setting.table.edit', compact('setting_table'));
    }

    public function update(Table $setting_table, TableUpdateRequest $request) {
    	$setting_table->update($request->all());
    	return redirect()->route('admin.setting_table.index')->withFlashSuccess('Table has been updated!');
    }
}
