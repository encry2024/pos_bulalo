<?php

namespace App\Http\Controllers\Backend\Commissary\Delivery;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commissary\Inventory\Inventory;
use App\Models\Commissary\Product\Product;
use App\Models\Commissary\Delivery\Delivery;
use App\Models\Branch\Branch;


class DeliveryController extends Controller
{
	public function index(){
		return view('backend.commissary.delivery.index');
	}

	public function create(){
		$ingredients = Inventory::all();
		$products  	 = Product::orderBy('name')->get()->pluck('name', 'id');

        $inventories = [];

        for($i = 0; $i < count($ingredients); $i++)
        {
            $name = '';

            if($ingredients[$i]->supplier == 'Other')
            {
                $name = $ingredients[$i]->other_inventory->name;
                $inventories[$ingredients[$i]->id] = $name;
            }
            else
            {
                $name = $ingredients[$i]->drygood_inventory->name;
                $inventories[$ingredients[$i]->id] = $name;
            }
        }
		// return $unions;
		return view('backend.commissary.delivery.create', compact('products', 'inventories'));
	}

	public function store(Request $request){
		$status = 0;

		if($request->item_type == 'PRODUCT')
		{
			$product = Product::findOrFail($request->item_id);

			if($product->produce >= $request->quantity)
			{
				$delivery = new Delivery();
				$delivery->item_id   = $request->item_id;
				$delivery->quantity  = $request->quantity;
				$delivery->date 	 = $request->date;
				$delivery->price 	 = $product->cost / $request->quantity;
				$delivery->type      = $request->item_type;
				$delivery->save();

				$product->produce = $product->produce - $request->quantity;
				$product->save();

				$status = 1;
			}
		}
		else
		{
			$inventory = Inventory::findOrFail($request->item_id);

			if($inventory->stock >= $request->quantity)
			{
				$delivery = new Delivery();
				$delivery->item_id   = $request->item_id;
				$delivery->quantity  = $request->quantity;
				$delivery->date 	 = $request->date;
				$delivery->price 	 = count($inventory->stocks) ? ($inventory->stocks->last()->price / $inventory->stocks->last()->quantity) : 0;
				$delivery->type      = $request->item_type;
				$delivery->save();

				$inventory->stock = $inventory->stock - $request->quantity;
				$inventory->save();

				$status = 1;
			}
		}

		if($status){
			return redirect()->route('admin.commissary.delivery.index')->withFlashSuccess('Item has been recorded!');
		}

		return redirect()->back()->withFlashDanger('Request quantity doesn\'t match from stock');
	}

	public function destroy(Delivery $delivery){
    	$delivery->delete();

    	return redirect()->route('admin.commissary.delivery.index')->withFlashDanger('Delivery record deleted Successfully!');
    }
}
