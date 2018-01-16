<?php

namespace App\Http\Controllers\Backend\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Inventory;
use App\Models\Category\Category;
use App\Models\Commissary\Product\Product as CommissaryProduct;
use App\Models\Commissary\Inventory\Inventory as CommissaryInventory;
use App\Models\Other\Inventory as OtherInventory;
use App\Models\DryGood\Inventory\Inventory as DryGoodInventory;

use App\Http\Requests\Backend\Inventory\StoreInventoryRequest;
use App\Http\Requests\Backend\Inventory\UpdateInventoryRequest;
use App\Repositories\Backend\Inventory\InventoryRepository;


use Carbon\Carbon;

class InventoryController extends Controller
{

    public function index(){
    	return view('backend.inventory.index');
    }

    public function create(){
        $categories     = Category::pluck('name', 'id');

        $inventories    = Inventory::where('supplier', 'Commissary Product')->get()->pluck('inventory_id');

        $products       = CommissaryProduct::whereNotIn('id', $inventories)->pluck('name', 'id');

        $inventories    = Inventory::where('supplier', 'DryGoods Material')->get()->pluck('inventory_id');

        $dry_goods      = DryGoodInventory::whereNotIn('id', $inventories)->pluck('name', 'id'); 

        $inventories    = Inventory::where('supplier', 'Commissary Raw Material')->get()->pluck('inventory_id');

        $ingredients    = CommissaryInventory::whereNotIn('id', $inventories)->get();

        $raws           = [];


        for($i = 0; $i < count($ingredients); $i++)
        {
            $name = '';

            if($ingredients[$i]->supplier == 'Other')
            {
                $name = $ingredients[$i]->other_inventory->name;

                $raws[$ingredients[$i]->id] = $name;
            }
            else
            {
                $name = $ingredients[$i]->drygood_inventory->name;

                $raws[$ingredients[$i]->id] = $name;
            }
        }

    	return view('backend.inventory.create', compact('categories', 'products', 'raws', 'dry_goods'));
    }

    public function store(StoreInventoryRequest $request){
        $others = '';
        
        if($request->supplier == 'Other')
        {
            $others = OtherInventory::updateOrCreate(['name' => $request->inventory_id]);
            $request['inventory_id'] = $others->id;
        }

    	Inventory::create($request->all());

    	return redirect()->route('admin.inventory.index')->withFlashSuccess('Inventory Added Successfully!');
    }

    public function edit(Inventory $inventory){
        $categories = Category::pluck('name', 'id');
        $name = '';

        if($inventory->supplier == 'Commissary Product')
        {
            $name = $inventory->commissary_product->name;
        }
        elseif($inventory->supplier == 'Commissary Raw Material')
        {
            if($inventory->commissary_inventory->supplier == 'Other')
                $name = $inventory->commissary_inventory->other_inventory->name;
            else
                $name = $inventory->commissary_inventory->drygood_inventory->name;
        }
        elseif($inventory->supplier == 'DryGoods Material')
        {
            $name = $inventory->dry_good_inventory->name;
        }
        else
        {
            $name = $inventory->other->name;
        }
        
    	return view('backend.inventory.edit', compact('name', 'inventory', 'categories'));
    }

    public function update(Inventory $inventory, UpdateInventoryRequest $request){
    	$inventory->update($request->all());

    	return redirect()->route('admin.inventory.index')->withFlashSuccess('Inventory Updated Successfully!');
    }

    public function destroy(Inventory $inventory){
    	$inventory->delete();

    	return redirect()->route('admin.inventory.index')->withFlashDanger('Inventory Deleted Successfully!');
    }

    public function getUnit($id, $supplier){
        if($supplier == 'Commissary Product')
        {
            return CommissaryProduct::findOrFail($id);
        }
        else if($supplier == 'Commissary Raw Material')
        {
            return CommissaryInventory::findOrFail($id);
        }
        else if($supplier == 'DryGoods Material')
        {
            return DryGoodInventory::findOrFail($id);
        }

        return 'none';
    }
}
