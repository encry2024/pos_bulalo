<?php

namespace App\Http\Controllers\Backend\Report\DryGood\Summary;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Order\Order;
use App\Models\OrderList\OrderList;
use App\Models\DryGood\Dispose\Dispose;
use App\Models\DryGood\GoodsReturn\GoodsReturn;
use App\Models\DryGood\Inventory\Inventory;
use App\Models\DryGood\Stock\Stock;
use App\Models\DryGood\Delivery\Delivery;
use App\Models\Inventory\Inventory as POS;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;

class ReportController extends Controller
{
    public function index(){
    	$to 	  = date('Y-m-d', strtotime('sunday'));
    	$from 	  = date('Y-m-d', strtotime($to.' -6 day'));
    	$reports  = $this->fetch_inventories($from, $to);
    	return view('backend.report.commissary.summary.index', compact('reports', 'from', 'to'));
    }

    public function store(Request $request){
        $to      = date('Y-m-d', strtotime($request->to));
        $from    = date('Y-m-d', strtotime($request->from));
        $reports = $this->fetch_inventories($from, $to);
        return view('backend.report.commissary.summary.index', compact('reports', 'from', 'to'));
    }

    public function fetch_inventories($from, $to){
        $reports     = [];
        $categories  = Category::all();

        foreach($categories as $category)
        {
            $index       = 0;
            $report      = [];
            $inventories = Inventory::where('category_id', $category->id)->get();

            foreach ($inventories as $inventory) 
            {
                $report[$index] = (object)[
                                    'name'      => $inventory->name,
                                    'unit'      => $inventory->unit_type,
                                    'beginning' => $this->beginning($inventory->id, $from, $to),
                                    'delivery'  => $this->delivery($inventory->id, $from, $to),
                                    'sale'      => $this->sales($inventory->id, $from, $to),
                                    'dispose'   => $this->dispose($inventory->id, $from, $to),
                                    'goods'     => $this->goods_return($inventory->id, $from, $to),
                                    'ending'    => $inventory->stock,
                                    'actual'    => $this->actual($inventory->id, $from, $to)
                                  ];
                $index++;
            }

            $reports[strtolower($category->name)] = (object)[
                                                        'category' => $category->name,
                                                        'summaries'  => $report
                                                    ];
        }

        return $reports;
    }

    public function beginning($inventory_id, $from, $to){
    	$cost     = 0;
        $quantity = 0;
        $used_qty = 0;
    	$sales    = $this->sales($inventory_id, $from, $to)->quantity;

    	$inventory= Inventory::with(['stocks' => function($q) use($from, $to) {
                        $q->whereBetween('received', [$from, $to])->withTrashed();
                    }])
                    ->where('id', $inventory_id)
                    ->withTrashed()
                    ->first();

        $deliveries = Delivery::where('item_id', $inventory->id)
                    ->withTrashed()
                    ->get();

        foreach($deliveries as $delivery)
        {
            $used_qty = $used_qty + $delivery->quantity;
        }

        $cost = $inventory->stocks->last()->price;

        $quantity = $inventory->stock + $used_qty;

    	$obj = (object)[
    					'quantity' => $quantity, 
    					'cost'	   => $cost
    				   ];

    	return $obj;
    }

    public function delivery($inventory_id, $from, $to){
    	$stocks = 0;
        $cost   = 0;

        $deliveries = Delivery::where('item_id', $inventory_id)
                    ->whereBetween('date', [$from, $to])
                    ->withTrashed()
                    ->get();

        foreach($deliveries as $delivery)
        {
            $stocks = $stocks + $delivery->quantity;
        }

        if(count($deliveries))
        {
            $cost = $deliveries->last()->price;
        }

        $objs = (object)[
                            'quantity' => $stocks,
                            'cost'     => $cost
                        ];

        return $objs;
    }

    public function sales($inventory_id, $from, $to){
    	$cost     = 0;
    	$total_qty= 0;

        $inv = Inventory::where('id', $inventory_id)->withTrashed()->first();

        $pos = POS::where('inventory_id', $inv->id)->withTrashed()->first();

        if(count($pos))
        {
            $orders = Order::with(
                            [
                                'order_list.product_size',
                                'order_list.product.product_size.ingredients' => 
                                    function($q) use ($pos) 
                                    {
                                        $q->where('inventory_product_size.inventory_id', $pos->id)->withTrashed();
                                    }
                            ])
                        ->whereHas('order_list.product.product_size.ingredients', 
                                    function($q) use($pos) 
                                    {
                                        $q->where('inventory_product_size.inventory_id', $pos->id)->withTrashed();
                                    })
                        ->whereBetween(DB::raw('date(created_at)'), [$from, $to])
                        ->where('status', 'Paid')
                        ->get();

            foreach ($orders as $order) 
            {
                $qty   = 0;
                $lists = $order->order_list;

                foreach ($lists as $list) 
                {
                    $size         = $list->product_size->size;
                    $product      = $list->product;
                    $product_size = $product->product_size->where('size', $size)->first();
                    $ingredients  = $product_size->ingredients;

                    foreach ($ingredients as $ingredient) 
                    {
                        $qty       = (int)$list->quantity;
                        $qty_use   = $ingredient->pivot->quantity;
                        $price     = $ingredient->stocks->last()->price; 
                        $cost      = $price;
                        $total_qty = $total_qty + ($qty * $qty_use);

                        if($ingredient->physical_quantity == 'Mass')
                        {
                            if($ingredient->unit_type != $ingredient->pivot->unit_type)
                            {
                                $stock_qty = new Mass(1, $ingredient->unit_type);
                                $req_qty   = new Mass($qty_use, $ingredient->pivot->unit_type);
                                $qty_left  = $stock_qty->subtract($req_qty);
                                $i = $qty_left->toUnit($ingredient->unit_type);
                                $actual_quantity = $req_qty->toUnit($ingredient->unit_type);
                                $cost = $price * $actual_quantity;
                            }
                            else
                            {
                                $cost = $qty_use * $price;
                            }
                        }
                        else
                        {
                            if($ingredient->unit_type != $ingredient->pivot->unit_type)
                            {
                                $stock_qty = new Volume(1, $ingredient->unit_type);
                                $req_qty   = new Volume($qty_use, $ingredient->pivot->unit_type);
                                $qty_left  = $stock_qty->subtract($req_qty)->toUnit($ingredient->unit_type);
                                $cost      = (1 - $qty_left) * $price;
                            }
                            else
                            {
                                $cost = $qty_use * $price;
                            }

                        }
                    }
                }            
            }
        }

		return (object)['cost' => $cost, 'quantity' => $total_qty];
    }

    public function dispose($inventory_id, $from, $to){
    	$quantity = 0;
    	$cost     = 0;
    	$disposes = Dispose::where('inventory_id', $inventory_id)
    					->whereBetween('date', [$from, $to])
                        ->withTrashed()
    					->get();

    	foreach ($disposes as $dispose) 
    	{
    		$quantity = $quantity + $dispose->quantity;
    	}

    	if(count($disposes))
    	{
			$cost = $disposes->last()->cost;
    	}
    	

    	$obj = (object)[
    						'quantity' => $quantity,
    						'cost'	   => $cost
    				   ];

    	return $obj;
    }

    public function goods_return($inventory_id, $from, $to){
    	$quantity = 0;
    	$cost     = 0;
    	$goods    = GoodsReturn::where('inventory_id', $inventory_id)
    					->whereBetween('date', [$from, $to])
                        ->withTrashed()
    					->get();

    	foreach ($goods as $good) 
    	{
    		$quantity = $quantity + $good->quantity;
    	}

    	if(count($goods))
    	{
    		$cost = $goods->last()->cost;
    	}

    	$objs = (object)[
    						'quantity' => $quantity,
    						'cost' 	   => $cost
    				    ];

    	return $objs;
    }

    public function actual($inventory_id, $from, $to){
    	$delivery  = $this->delivery($inventory_id, $from, $to);
    	$inventory = Inventory::with(['stocks' => function($q) use($from, $to) {
                        $q->whereBetween('received', [$from, $to])->withTrashed();
                    }])
                    ->where('id', $inventory_id)->withTrashed()->first();

    	// return ($inventory->stock + (count($inventory->stocks) ? $inventory->stocks->sum('quantity') : 0));
        return $inventory->stock;
    }





    /**************************************************************************/
    /*                      COMMISSARY PRODUCT                                */
    /**************************************************************************/

     public function product_beginning($product_id, $from, $to){
        $cost     = 0;
        $quantity = 0;
        $made     = 0;

        $product  = Product::with(
                            [
                                'produced' => function($q) use($from, $to) 
                                            {
                                                $q->whereBetween('date', [$from, $to])->withTrashed();
                                            }
                            ])
                            ->where('id', $product_id)->first();

        foreach($product->produced as $produce)
        {
            $made = $made + $produce->quantity;            
        }

        $quantity = ($product->produce + $made);

        $obj = (object)[
                        'quantity' => $quantity, 
                        'cost'     => $product->cost
                       ];

        return $obj;
    }

    public function product_delivery($product_id, $from, $to){
        $stocks = 0;
        $cost   = 0;

        $deliveries = Delivery::where('item_id', $product_id)
                    ->whereBetween('date', [$from, $to])
                    ->where('type', 'PRODUCT')
                    ->withTrashed()
                    ->get();

        if(count($deliveries))
        {
            $product = Product::where('id', $deliveries->first()->item_id)->withTrashed()->first();
        }

        foreach($deliveries as $delivery)
        {
            $stocks = $stocks + $delivery->quantity;
        }

        if(!empty($product))
        {
            $cost = $product->cost;
        }
        

        $objs = (object)[
                            'quantity' => $stocks,
                            'cost'     => $cost
                        ];

        return $objs;
    }

    public function product_sales($product_id, $from, $to){
        $cost     = 0;
        $total_qty= 0;

        $cproduct = Product::where('id', $product_id)
                    ->withTrashed()
                    ->first();

        $pos = POS::where('inventory_id', $product_id)
                    ->where('supplier', 'Commissary Product')
                    ->withTrashed()
                    ->first();

        if(count($pos))
        {
            $orders = Order::with(
                            [
                                'order_list.product_size',
                                'order_list.product.product_size.ingredients' => 
                                    function($q) use ($pos) 
                                    {
                                        $q->where('inventory_product_size.inventory_id', $pos->id)
                                        ->withTrashed();
                                    }
                            ])
                        ->whereHas('order_list.product.product_size.ingredients', 
                                    function($q) use($pos) 
                                    {
                                        $q->where('inventory_product_size.inventory_id', $pos->id)
                                        ->withTrashed();
                                    })
                        ->whereBetween(DB::raw('date(created_at)'), [$from, $to])
                        ->where('status', 'Paid')
                        ->get();

            foreach ($orders as $order) 
            {
                $qty   = 0;
                $lists = $order->order_list;

                foreach ($lists as $list) 
                {
                    $size         = $list->product_size->size;
                    $product      = $list->product;
                    $product_size = $product->product_size->where('size', $size)->first();
                    $ingredients  = $product_size->ingredients;

                    foreach ($ingredients as $ingredient) 
                    {
                        $qty       = (int)$list->quantity;
                        $qty_use   = $ingredient->pivot->quantity;
                        $price     = $cproduct->cost;
                        $cost      = $price;
                        $total_qty = $total_qty + ($qty * $qty_use);

                        if($ingredient->physical_quantity == 'Mass')
                        {
                            if($ingredient->unit_type != $ingredient->pivot->unit_type)
                            {
                                $stock_qty = new Mass(1, $ingredient->unit_type);

                                $req_qty   = new Mass($qty_use, $ingredient->pivot->unit_type);

                                $qty_left  = $stock_qty->subtract($req_qty)->toUnit($ingredient->unit_type);

                                $cost      = (1 - $qty_left) * $price;
                            }
                            else
                            {
                                // $cost = $qty_use * $price;
                            }
                        }
                        else
                        {
                            if($ingredient->unit_type != $ingredient->pivot->unit_type)
                            {
                                $stock_qty = new Volume(1, $ingredient->unit_type);

                                $req_qty   = new Volume($qty_use, $ingredient->pivot->unit_type);

                                $qty_left  = $stock_qty->subtract($req_qty)->toUnit($ingredient->unit_type);

                                // $cost      = (1 - $qty_left) * $price;
                            }
                            else
                            {
                                $cost = $qty_use * $price;
                            }

                        }
                    }
                }            
            }
        }

        return (object)['cost' => $cost, 'quantity' => $total_qty];
    }

     public function product_dispose($product_id, $from, $to){
        $quantity = 0;
        $cost     = 0;
        $disposes = Dispose::where('inventory_id', $product_id)
                        ->whereBetween('date', [$from, $to])
                        ->where('type', 'Product')
                        ->withTrashed()
                        ->get();

        foreach ($disposes as $dispose) 
        {
            $quantity = $quantity + $dispose->quantity;
        }

        if(count($disposes))
        {
            $cost = $disposes->last()->cost;
        }
        

        $obj = (object)[
                            'quantity' => $quantity,
                            'cost'     => $cost
                       ];

        return $obj;
    }




    public function getSundays($from, $to){
        $sundays  = [];
        $index    = 0;
        $from_day = date('d', strtotime($from));
        $from_mon = date('m', strtotime($from));
        $from_yr  = date('Y', strtotime($from));

        $to_day   = date('d', strtotime($to));
        $to_mon   = date('m', strtotime($to));
        $to_yr    = date('Y', strtotime($to));

        if(($to_mon - $from_mon) == 0)
        {
            for($i = $from_day - 1; $i < $to_day; $i++)
            {
                $date = date('Y-m-d', strtotime($from.' +'.$i.' day'));
                
                if($this->isSunday($from, $date))
                {
                    $sundays[$index] = $date;
                    $index++;
                }
            }
        }
        elseif(($to_mon - $from_mon) > 0)
        {

            for($x = 0; $x <= ($to_mon - $from_mon); $x++)
            {
                $month = $from_yr.'-'.($from_mon + $x).'-'.$from_day;

                for($i = $from_day - 1; $i < $to_day; $i++)
                {
                    $date = date('Y-m-d', strtotime($month.' +'.$i.' day'));

                    if($this->isSunday($month, $date))
                    {
                        if(array_search($date, $sundays) == false)
                        {
                            $sundays[$index] = $date;
                            $index++;
                        }
                    }
                }

            }
        }

        return $sundays;
    }

    public function isSunday($mon, $val){

        for($i = 1; $i <= 6; $i++)
        {
            $sunday = date('Y-m-d', strtotime($mon.' +'.$i.' sunday'));

            if($val === $sunday)
            {
                return TRUE;
            }

        }

        return FALSE;
    }
}
