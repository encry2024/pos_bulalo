<?php

namespace App\Http\Controllers\Backend\Commissary\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Commissary\Inventory\Inventory;
use App\Models\Commissary\Other\Inventory as Other;
use App\Models\DryGood\Inventory\Inventory as DryGood;


use App\Http\Requests\Backend\Inventory\StoreInventoryRequest;

class InventoryController extends Controller
{
    public function index(){
    	return view('backend.commissary.inventory.index');
    }

    public function create(){
        $categories = Category::pluck('name', 'id');
        $dry_goods  = DryGood::pluck('name', 'id');

    	return view('backend.commissary.inventory.create', compact('categories', 'dry_goods'));
    }

    public function store(StoreInventoryRequest $request){
    	$item = null;

        if($request->supplier == 'Other')
        {
            $other = new Other();
            $other->name= $request->inventory_id;
            $other->save();

            $item = $other;
        }
        else
        {
            $item = DryGood::findOrFail($request->inventory_id);
        }

        $inventory                    = new Inventory();
        $inventory->inventory_id      = $item->id;
        $inventory->unit_type         = $request->unit_type;
        $inventory->reorder_level     = $request->reorder_level;
        $inventory->category_id       = $request->category_id;
        $inventory->physical_quantity = $request->physical_quantity;
        $inventory->supplier          = $request->supplier;
        $inventory->save();

    	return redirect()->route('admin.commissary.inventory.index');
    }

    public function edit(Inventory $inventory){
    	$categories = Category::pluck('name', 'id');
        
    	return view('backend.commissary.inventory.edit', compact('categories', 'inventory'));
    }

    public function update(Inventory $Inventory, Request $request){
    	$Inventory->update([
    		'reorder_level' => $request->reorder_level,
            'category_id'   => $request->category_id
    	]);

    	return redirect()->route('admin.commissary.inventory.index')->withFlashSuccess('Inventory Updated Successfully!');
    }

    public function destroy(Inventory $Inventory){
    	$Inventory->delete();

    	return redirect()->route('admin.commissary.inventory.index')->withFlashDanger('Inventory Deleted Successfully!');
    }

    public function getUnit($id, $supplier){
        if($supplier == 'DryGoods Material')
        {
            return DryGood::findOrFail($id);
        }

        return 'none';
    }
}
