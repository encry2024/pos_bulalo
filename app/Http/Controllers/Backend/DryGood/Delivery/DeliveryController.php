<?php

namespace App\Http\Controllers\Backend\DryGood\Delivery;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DryGood\Inventory\Inventory;
use App\Models\DryGood\Product\Product;
use App\Models\DryGood\Delivery\Delivery;
use App\Models\Branch\Branch;
use App\Models\Notification\Notification;

class DeliveryController extends Controller
{
	public function index()
    {
		return view('backend.dry_good.delivery.index');
	}

	public function create()
    {
		$inventories = Inventory::orderBy('name')->get();

		return view('backend.dry_good.delivery.create', compact('inventories'));
	}

	public function store(Request $request)
    {
		$inventory = Inventory::find($request->item_id);

        if(empty($request->item_id)) {
            return redirect()->back()->withFlashDanger('Please choose an Item.');
        } else {
            if($inventory->stock >= $request->quantity) {
                $delivery 			    = new Delivery();
                $delivery->item_id      = $request->item_id;
                $delivery->quantity     = $request->quantity;
                $delivery->date 	        = $request->date;
                $delivery->deliver_to   = $request->deliver_to;
                $delivery->price 	    = count($inventory->stocks) ? $inventory->stocks->last()->price : 0;
                $delivery->save();

                $inventory->stock = $inventory->stock - $request->quantity;
                $inventory->save();

                $this->notification();
                return redirect()->route('admin.dry_good.delivery.index')->withFlashSuccess('Item has been recorded!');
            }
            return redirect()->back()->withFlashDanger('Check item stock!');
        }
	}

	public function destroy(Delivery $delivery)
    {
		$inventory = $delivery->inventory;
		$inventory->stock = $inventory->stock + $delivery->quantity;
		$inventory->save();
		$delivery->delete();

		return redirect()->route('admin.dry_good.delivery.index')->withFlashDanger('Stock has Been Deleted Successfully!');
	}

    public function notification()
	{
    	$inventories = Inventory::whereRaw('stock < reorder_level')->get();
        foreach ($inventories as $inventory) 
        {
            $desc = $inventory->name.' has '.$inventory->stock.' stocks left.';

            Notification::updateOrCreate(
                [
                    'name' => $inventory->name,
                    'date' => date('Y-m-d'), 
                    'description' => $desc,
                    'stock_from' => 'DRY GOODS',
                    'status' => 'new'
                ],
                [
                    'inventory_id' => $inventory->id
                ]
            ); 
    	}
	}

    public function getItem(Request $request)
    {
        return \GuzzleHttp\json_encode(Inventory::find($request->inventory_id));
    }
}
