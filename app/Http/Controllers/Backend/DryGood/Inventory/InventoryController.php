<?php

namespace App\Http\Controllers\Backend\DryGood\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\DryGood\Inventory\Inventory;

use App\Http\Requests\Backend\Inventory\StoreInventoryRequest;

class InventoryController extends Controller
{
    public function index(){
    	return view('backend.dry_good.inventory.index');
    }

    public function create(){
    	$categories = Category::pluck('name', 'id');

    	return view('backend.dry_good.inventory.create', compact('categories'));
    }

    public function store(StoreInventoryRequest $request){
    	Inventory::updateOrCreate(
    		['name' => $request->name],
    		[
                'unit_type'          => $request->unit_type,
                'physical_quantity'  => $request->physical_quantity,
    			'reorder_level'      => $request->reorder_level,
    			'category_id'        => $request->category_id
    		]
    	);

    	return redirect()->route('admin.dry_good.inventory.index');
    }

    public function edit(Inventory $inventory){
    	$categories = Category::pluck('name', 'id');

    	return view('backend.dry_good.inventory.edit', compact('categories', 'inventory'));
    }

    public function update(Inventory $Inventory, Request $request){
    	$Inventory->update([
    		'name' 			=> $request->name,
    		'reorder_level' => $request->reorder_level,
            'category_id'   => $request->category_id
    	]);

    	return redirect()->route('admin.dry_good.inventory.index')->withFlashSuccess('Inventory Updated Successfully!');
    }

    public function destroy(Inventory $Inventory){
    	$Inventory->delete();

    	return redirect()->route('admin.dry_good.inventory.index')->withFlashDanger('Inventory Deleted Successfully!');
    }
}
