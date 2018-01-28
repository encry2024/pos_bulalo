<?php

namespace App\Http\Controllers\Backend\Report\POS\ItemUse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Inventory\Inventory;
use App\Models\Stock\Stock;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;

class ReportController extends Controller
{
    public function index(){
    	$from    = date('Y-m-d');
        $to 	 = date('Y-m-d');
		$reports = $this->get_report($from, $to);
		
    	return view('backend.report.pos.item_use.index', compact('reports', 'from','to'));
    }

    public function get_report($from, $to) {
    	$reports = [];
    	$index   = 0;

    	foreach(Inventory::all() as $inventory)
		{
			$quantity  = 0;
			$price     = 0;
			$item_name = '';
			$unit_Type = '';
			$orders    = $this->get_sale($inventory->id, $from, $to);
			$stock     = Stock::where('inventory_id', $inventory->id)
						->where('received', $from)
						->first();

			if(!empty($stock))
			{
				$price = $stock->price;
			}

			foreach($orders as $order)
			{
				foreach($order->order_list as $list)
				{

					foreach($list->product_size->ingredients as $ingredient)
					{
						$quantity += $list->quantity * $ingredient->pivot->quantity;
						$unit_type = $ingredient->pivot->unit_type;
					}
				}
			}

			if($inventory->supplier == 'Commissary Product')
            {
                $name = $inventory->commissary_product->name;
            }
            elseif($inventory->supplier == 'Commissary Raw Material')
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
            elseif($inventory->supplier == 'DryGoods Material')
            {
                $name = $inventory->dry_good_inventory->name;
            }
            else
            {
                $name = $inventory->other->name;
            }

            if($inventory->physical_quantity == 'Mass')
	        {
	            $stock_qty = new Mass(0, $inventory->unit_type);
	            $req_qty   = new Mass($quantity, $unit_type);
	            $quantity  = $stock_qty->add($req_qty)->toUnit($inventory->unit_type);
	        }
	        elseif($inventory->physical_quantity == 'Volume')
	        {
	            $stock_qty = new Volume(0, $inventory->unit_type);
	            $req_qty   = new Volume($quantity, $inventory->unit_type);
	            $quantity  = $stock_qty->add($req_qty)->toUnit($inventory->unit_type);
	        }

			$reports[$index] = (object)['name' => $name, 'quantity' => $quantity, 'price' => $price, 'supplier' => $inventory->supplier];
			$index++;
		}

		return $reports;
    }

    public function get_sale($inventory_id, $from, $to){
    	$orders = Order::with(
    		['order_list.product.product_size.ingredients' => function($q) use($inventory_id)
		    	{
		    		$q->where('inventory_product_size.inventory_id', $inventory_id);
		    	}
		    ])->whereHas('order_list.product.product_size.ingredients', function($q) use($inventory_id)
		    	{
		    		$q->where('inventory_product_size.inventory_id', $inventory_id);
		    	}
		    )->whereRaw('date(created_at) between "'.$from.'" and "'.$to.'"')
		    ->get();

		return $orders;
    }
}
