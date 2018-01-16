<?php

namespace App\Http\Controllers\Backend\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Inventory\Inventory;
use App\Models\Stock\Stock;
use App\Models\Product\Product;
use App\Models\ProductSize\ProductSize;
use App\Models\Commissary\Delivery\Delivery;
use App\Models\Commissary\History\History;

use App\Models\DryGood\Delivery\Delivery as DryGoodDelivery;

use App\Http\Requests\Backend\Stock\ManageRequest;
use Illuminate\Support\Facades\Auth;

use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;

class StockController extends Controller
{
    
	public function index(){
		return view('backend.stock.index');
	}

	public function create(){
		$inventories = Inventory::all();
        $ingredients = [];
        $selections  = [];

        foreach($inventories as $inventory)
        {
            $name = '';
            $temp = [];

            if($inventory->supplier == 'Other')
            {
                $name = $inventory->other->name;
            }
            elseif($inventory->supplier == 'Commissary Product')
            {
                $name = $inventory->commissary_product->name;
            }
            elseif($inventory->supplier == 'DryGoods Material')
	        {
	            $name = $inventory->dry_good_inventory->name;
	        }
            else
            {
            	if($inventory->commissary_inventory->supplier == 'Other')
            	{
            		$name = $inventory->commissary_inventory->other_inventory->name;
            	}
            	else
            	{
            		$name = $inventory->commissary_inventory->drygood_inventory->name;
            	}
            }

            $selections[$inventory->id] = $name;
            $temp = ['id' => $inventory->id, 'name' => $name];

            array_push($ingredients, $temp);
        }

        $inventories = (object)$selections;
		
		return view('backend.stock.create', compact('inventories'));
	}

	public function store(ManageRequest $request){
		$stock 		= 0;
		$inventory 	= Inventory::find($request->inventory_id);
		
		// check if ingredient is from commissary
		
		if($inventory->supplier == 'Commissary Product')
		{
			$delivery = Delivery::where('item_id', $inventory->inventory_id)
						->where('status', 'NOT RECEIVED')
						->where('type', 'PRODUCT')
						->where('quantity', $request->quantity)
						->first();

			if(!count($delivery))
			{
				return redirect()->route('admin.stock.create')->withFlashDanger('Request quantity does not match from delivered quantity!');
			}

			$delivery->status = 'RECEIVED';
			$delivery->save();

			$history 				= new History();
			$history->product_id 	= $delivery->item_id;
			$history->description 	= 'Stored '.$request->quantity.' '.$delivery->product->name;
			$history->status 		= 'Minus';
			$history->save();

			$inventory->AddStock($request->quantity);
			$inventory->save();
		}
		elseif($inventory->supplier == 'Commissary Raw Material')
		{
			$delivery = Delivery::where('item_id', $inventory->inventory_id)
						->where('status', 'NOT RECEIVED')
						->where('type', 'RAW MATERIAL')
						->where('quantity', $request->quantity)
						->first();

			if(!count($delivery))
			{
				return redirect()->route('admin.stock.create')->withFlashDanger('Request quantity does not match from delivered quantity!');
			}

			if($delivery->quantity >= $request->quantity)
			{
				$delivery->status = 'RECEIVED';
				$delivery->save();

				if($inventory->physical_quantity == 'Mass')
		        {
		            $stock_qty = new Mass($inventory->stock, $inventory->unit_type);

		            $req_qty   = new Mass($delivery->quantity, $inventory->unit_type);

		            $qty_left  = $stock_qty->add($req_qty);

		            $stock     = $qty_left->toUnit($inventory->unit_type);
		        }
		        elseif($inventory->physical_quantity == 'Volume')
		        {
		            $stock_qty = new Volume($inventory->stock, $inventory->unit_type);

		            $req_qty   = new Volume($delivery->quantity, $inventory->unit_type);

		            $qty_left  = $stock_qty->add($req_qty);

		            $stock     = $qty_left->toUnit($inventory->unit_type);
		        }
		        else
		        {
		        	$stock = $inventory->stock + $request->quantity;
		        }
			}

			$inventory->stock = $stock;
			$inventory->save();
		}
		elseif($inventory->supplier == 'DryGoods Material')
		{
			$delivery = DryGoodDelivery::where('item_id', $inventory->inventory_id)
						->where('status', 'NOT RECEIVED')
						->where('deliver_to', 'POS')
						->where('quantity', $request->quantity)
						->first();

			if(!count($delivery))
			{
				return redirect()->route('admin.stock.create')->withFlashDanger('Request quantity does not match from delivered quantity!');
			}

			if($delivery->quantity >= $request->quantity)
			{
				$delivery->status = 'RECEIVED';
				$delivery->save();

				if($inventory->physical_quantity == 'Mass')
		        {
		            $stock_qty = new Mass($inventory->stock, $inventory->unit_type);

		            $req_qty   = new Mass($delivery->quantity, $inventory->unit_type);

		            $qty_left  = $stock_qty->add($req_qty);

		            $stock = $qty_left->toUnit($inventory->unit_type);
		        }
		        elseif($inventory->physical_quantity == 'Volume')
		        {
		            $stock_qty = new Volume($inventory->stock, $inventory->unit_type);

		            $req_qty   = new Volume($delivery->quantity, $inventory->unit_type);

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
		}
		else
		{
			$inventory->stock = $inventory->stock + $request->quantity;
			$inventory->save();
		}

		Stock::create($request->all());

		$this->updateProductCost();

		return redirect()->route('admin.stock.index')->withFlashSuccess('Stock Added Successfully!');
	}

	public function edit(Stock $stock){
		$inventory = $stock->inventory;
		$name      = '';

		if($inventory->supplier == 'Commissary Product')
		{
			$name = $inventory->commissary_product->name;
		}
		elseif($inventory->supplier == 'Commissary Raw Material')
		{
			$name = $inventory->commissary_inventory->name;
		}
		else
		{
			$name = $inventory->other->name;
		}

		return view('backend.stock.edit', compact('name', 'stock'));
	}

	public function update(Stock $stock, ManageRequest $request){
		$stock->inventory_id	= $request->inventory_id;
		$stock->quantity		= $request->quantity;
		$stock->price			= $request->price;
		$stock->received		= $request->received;
		$stock->expiration		= $request->expiration;
		$stock->status			= $request->status.($request->status == 'EXPIRE' ? 'D' :'ED');
		$stock->save();


		$inventory = Inventory::find($request->inventory_id);
		$inventory->stock = $inventory->stock - $stock->quantity;
		$inventory->save();

		return redirect()->route('admin.stock.index')->withFlashSuccess('Stock Updated Successfully!');
	}

	public function destroy(Stock $stock){
		$inventory = $stock->inventory;
		
		if($inventory->supplier == 'Commissary Raw Material')
		{
			$delivery = Delivery::where('item_id', $inventory->commissary_product->id)->first();
			$delivery->status = 'NOT RECEIVED';
			$delivery->save();
		}
		elseif($inventory->supplier == 'Commissary Product')
		{
			$delivery = Delivery::where('item_id', $inventory->commissary_inventory->id)->first();
			$delivery->status = 'NOT RECEIVED';
			$delivery->save();
		}

		$inventory->stock = $inventory->stock - $stock->quantity;
		$inventory->save();

		$stock->delete();

		return redirect()->route('admin.stock.index')->withFlashDanger('Stock has Been Deleted Successfully!');
	}

	public function updateProductCost(){
		$products = Product::with('product_size', 'product_size.ingredients')->orderBy('id', 'desc')->get();

		foreach ($products as $product) 
		{
			$product_sizes = $product->product_size;

			foreach($product_sizes as $product_size)
			{
				$total        = 0;
				$product_cost = 0;
				$ingredients  = $product_size->ingredients;

				foreach($ingredients as $ingredient)
				{

					$qty_left 	= 0;
					$price    	= 0;
					$last_stock = 0;

					if(count($ingredient->stocks))
	                {
	                    $price      = $ingredient->stocks->last()->price;
	                    $last_stock = $ingredient->stocks->last()->quantity;
	                }

					if($ingredient->physical_quantity == 'Mass')
					{
					    if($ingredient->unit_type == $ingredient->pivot->quantity)
	                    {
	                        $qty_left = $ingredient->pivot->quantity;
	                    }
	                    else
	                    {
	                        $stock_qty = new Mass(1, $ingredient->unit_type);

	                        $req_qty   = new Mass($ingredient->pivot->quantity, $ingredient->pivot->unit_type);

	                        $qty_left  = $stock_qty->subtract($req_qty)->toUnit($ingredient->unit_type);
	                    }

					}
					elseif($ingredient->physical_quantity == 'Volume')
					{
					    if($ingredient->unit_type == $ingredient->pivot->quantity)
	                    {
	                        $qty_left = $ingredient->pivot->quantity;
	                    }
	                    else
	                    {
	                        $stock_qty = new Mass(1, $ingredient->unit_type);

	                        $req_qty   = new Mass($ingredient->pivot->quantity, $ingredient->pivot->unit_type);

	                        $qty_left  = $stock_qty->subtract($req_qty)->toUnit($ingredient->unit_type);
	                    }
					}
					else
					{
					    $qty_left = 1;
					}


					if($price != 0 && $last_stock != 0)
					{
						if($qty_left < 0 || $qty_left == 0)
							$qty_left = $ingredient->pivot->quantity;

	                    $total = ($price / $last_stock) * $qty_left;

					}
	                else
	                    $total = 0;

	                $product_cost = $product_cost + $total;

				}

				$product_size->cost = $product_cost;
				$product_size->save();
			}

		}

	}

}
