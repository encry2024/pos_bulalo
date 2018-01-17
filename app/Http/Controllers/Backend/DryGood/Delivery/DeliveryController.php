<?php

namespace App\Http\Controllers\Backend\DryGood\Delivery;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DryGood\Inventory\Inventory;
use App\Models\DryGood\Product\Product;
use App\Models\DryGood\Delivery\Delivery;
use App\Models\Branch\Branch;


class DeliveryController extends Controller
{
	public function index(){
		return view('backend.dry_good.delivery.index');
	}

	public function create(){
		$inventories = Inventory::orderBy('name')->get()->pluck('name', 'id');
		// return $unions;
		return view('backend.dry_good.delivery.create', compact('inventories'));
	}

	public function store(Request $request){
		$inventory = Inventory::findOrFail($request->item_id);

		if($inventory->stock >= $request->quantity)
		{
			$delivery 			 = new Delivery();
			$delivery->item_id   = $request->item_id;
			$delivery->quantity  = $request->quantity;
			$delivery->date 	 = $request->date;
			$delivery->deliver_to= $request->deliver_to;
			$delivery->price 	 = count($inventory->stocks) ? ($inventory->stocks->last()->price / $inventory->stocks->last()->quantity) : 0;
			$delivery->save();

			$inventory->stock = $inventory->stock - $request->quantity;
			$inventory->save();

			return redirect()->route('admin.dry_good.delivery.index')->withFlashSuccess('Item has been recorded!');
		}
		return redirect()->back()->withFlashDanger('Check item stock!');
	}

	public function destroy(Delivery $delivery){
		$inventory = $delivery->inventory;
		$inventory->stock = $inventory->stock + $delivery->quantity;
		$inventory->save();
		$delivery->delete();


		return redirect()->route('admin.dry_good.delivery.index')->withFlashDanger('Stock has Been Deleted Successfully!');
	}
}
