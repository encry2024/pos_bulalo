<?php

namespace App\Http\Controllers\Backend\Commissary\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commissary\Stock\Stock;
use App\Models\Commissary\Inventory\Inventory;
use App\Models\DryGood\Delivery\Delivery;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;

class StockController extends Controller
{
    public function index(){
    	return view('backend.commissary.stock.index');
    }

    public function create(){
    	$ingredients = Inventory::all();
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

    	return view('backend.commissary.stock.create', compact('inventories'));
    }

    public function store(Request $request){
		$inventory = Inventory::find($request->inventory_id);
		$stock     = 0;


		if($inventory->supplier == 'DryGoods Material')
		{
			$delivery = Delivery::where('item_id', $inventory->inventory_id)
						->where('quantity', $request->quantity)
						->where('deliver_to', 'Commissary')
						->where('status', 'NOT RECEIVED')
						->first();

			if(count($delivery) == 0)
			{
				return redirect()->back()->withFlashDanger('Check DryGoods delivered quantity!');
			}
			else
			{
				$delivery->status = 'RECEIVED';
				$delivery->save();

				$drygood = $delivery->inventory;

				if($inventory->physical_quantity == 'Mass')
		        {
		            $stock_qty = new Mass($inventory->stock, $inventory->unit_type);

		            $req_qty   = new Mass($delivery->quantity, $drygood->unit_type);

		            $qty_left  = $stock_qty->add($req_qty);

		            $stock = $qty_left->toUnit($inventory->unit_type);
		        }
		        elseif($inventory->physical_quantity == 'Volume')
		        {
		            $stock_qty = new Volume($inventory->stock, $inventory->unit_type);

		            $req_qty   = new Volume($delivery->quantity, $drygood->unit_type);

		            $qty_left  = $stock_qty->add($req_qty);

		            $stock = $qty_left->toUnit($inventory->unit_type);
		        }
		        else
		        {
		        	$stock = $inventory->stock + $request->quantity;
		        }
			}
		}
		else 
		{
			if($inventory->physical_quantity == 'Mass')
	        {
	            $stock_qty = new Mass($inventory->stock, $inventory->unit_type);

	            $req_qty   = new Mass($request->quantity, $inventory->unit_type);

	            $qty_left  = $stock_qty->add($req_qty);

	            $stock = $qty_left->toUnit($inventory->unit_type);
	        }
	        elseif($inventory->physical_quantity == 'Volume')
	        {
	            $stock_qty = new Volume($inventory->stock, $inventory->unit_type);

	            $req_qty   = new Volume($request->quantity, $inventory->unit_type);

	            $qty_left  = $stock_qty->add($req_qty);

	            $stock = $qty_left->toUnit($inventory->unit_type);
	        }
	        else
	        {
	        	$stock = $inventory->stock + $request->quantity;
	        }
		}
		

		$inventory->stock = $stock;
		$inventory->save();

		
    	Stock::create($request->all());

		return redirect()->route('admin.commissary.stock.index')->withFlashSuccess('Stock added to inventory');
    }

    public function edit(Stock $stock){

		return view('backend.commissary.stock.edit', compact('stock'));
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

		return redirect()->route('admin.commissary.stock.index')->withFlashSuccess('Stock Updated Successfully!');
	}

	public function destroy(Stock $stock){
		$stock->delete();

		return redirect()->route('admin.commissary.stock.index')->withFlashDanger('Stock has Been Deleted Successfully!');
	}
}
