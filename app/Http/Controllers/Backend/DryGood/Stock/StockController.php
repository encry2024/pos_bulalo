<?php

namespace App\Http\Controllers\Backend\DryGood\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DryGood\Stock\Stock;
use App\Models\DryGood\Inventory\Inventory;

class StockController extends Controller
{
    public function index(){
    	return view('backend.dry_good.stock.index');
    }

    public function create(){
    	$inventories = Inventory::pluck('name', 'id');

    	return view('backend.dry_good.stock.create', compact('inventories'));
    }

    public function store(Request $request){
    	Stock::create($request->all());

		$inventory = Inventory::find($request->inventory_id);
		$inventory->AddStock($request->quantity);
		$inventory->save();

		return redirect()->route('admin.dry_good.stock.index');
    }

    public function edit(Stock $stock){

		return view('backend.dry_good.stock.edit', compact('stock'));
	}

	public function update(Stock $stock, Request $request){
		$stock->update([
			'inventory_id'	=> $request->inventory_id,
			'quantity'		=> $request->quantity,
			'price'			=> $request->price,
			'received'		=> $request->received,
			'expiration'	=> $request->expiration
		]);

		$stock = Stock::selectRaw('sum(quantity) as "quantity"')
				->where('inventory_id', $request->inventory_id)
				->where('status', 'NEW')
				->first();

		$inventory = Inventory::find($request->inventory_id);
		$inventory->stock = $stock->quantity;
		$inventory->save();

		return redirect()->route('admin.dry_good.stock.index')->withFlashSuccess('Stock Updated Successfully!');
	}

	public function destroy(Stock $stock){
		$stock->delete();

		return redirect()->route('admin.dry_good.stock.index')->withFlashDanger('Stock has Been Deleted Successfully!');
	}
}
