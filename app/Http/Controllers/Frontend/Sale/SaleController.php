<?php

namespace App\Http\Controllers\Frontend\Sale;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Order\Order;
use App\Models\OrderList\OrderList;
use App\Models\Inventory\Inventory;
use App\Models\ProductSize\ProductSize;
use App\Models\Notification\Notification;
use App\Models\Setting\Table;

use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Volume;

use Auth;
use DB;

class SaleController extends Controller
{
    public function index()
    {
        $total  = 0;
        $orders = Order::where(Db::raw('date(created_at)'), date('Y-m-d'))
                  ->where('user_id', Auth::user()->id)
                  ->where('status','Paid')
                  ->orderBy('created_at', 'desc')
                  ->get();

        foreach ($orders as $order) {
            $total = $total + $order->payable;
        }

        return view('frontend.user.sale.daily', compact('total', 'orders'));
    }

    public function monthly()
    {
        $total  = 0;
        $date   = date('F Y');
        $orders = Order::whereBetween(Db::raw('date(created_at)'), [date('Y-m-01'), date('Y-m-31')])
                  ->where('user_id', Auth::user()->id)
                  ->where('status', 'Paid')
                  ->orderBy('created_at', 'desc')
                  ->get();

        foreach ($orders as $order) {
            $total = $total + $order->payable;
        }

        return view('frontend.user.sale.monthly', compact('total', 'orders', 'date'));
    }

    # SAVE FUNCTION
    public function save(Request $request){
    	$arr 	 = json_decode($request->orders);

    	// return json_encode($request->orders);

    	// "[{\"id\":\"2\",\"code\":\"Coke\",\"price\":\"30.00\",\"qty\":\"1\",\"size\":\"Short Order\"}]"

        $p_avail = 0; //available product

        if(empty($request->transaction_no))
        {
            for($i = 0; $i < count($arr); $i++)
            {
                  $status = $this->product_availability($arr[$i]->id, $arr[$i]->qty);
                  if($status == 'Available')
                    $p_avail++;
            }
        }
        //
        // check if ordered products is equals to available products
        //
        if(count($arr) == $p_avail || !empty($request->transaction_no))
        {
            if(empty($request->transaction_no))
                $request->transaction_no = date('y-m').rand(100000, 999999);

            $order = Order::updateOrCreate(
                [
                    'transaction_no' => $request->transaction_no,
                    'table_no'       => $request->table
                ],
                [
                    'cash'      => $request->cash,
                    'change'    => $request->change,
                    'charge'    => $request->charge,
                    'payable'   => $request->payable,
                    'discount'  => $request->discount,
                    'vat'       => $request->vat,
                    'total'     => $request->amount_due,
                    'type'      => $request->order_type,
                    'status'    => ($request->order_type == 'Take Out' ? 'Paid':'Unpaid'),
                    'user_id'   => Auth::user()->id
                ]
            );
            //
            // set stock use
            //
            for($i = 0; $i < count($arr); $i++)
            {
                $size = ProductSize::where('product_id', $arr[$i]->id)->first();

                $list = OrderList::updateOrCreate(
                    [
                        'order_id'          => $order->id,
                        'product_id'        => $arr[$i]->id,
                        'product_size_id'   => $size->id
                    ],
                    [
                        'price'     => $arr[$i]->price,
                        'quantity'  => $arr[$i]->qty
                    ]
                );

                $product      = Product::findOrFail($list->product_id);
                $product_size = $product->product_size;

                foreach ($product_size as $size) {
                    $inventories = $size->ingredients;

                    foreach($inventories as $inventory){
                        $qty_left  = 0;
                        $stock_dec = 0;

                        if($inventory->physical_quantity == 'Mass')
                        {
                            $stock_qty = new Mass($inventory->stock, $inventory->unit_type);
                            $req_qty   = new Mass(($list->quantity * $inventory->pivot->quantity), $inventory->pivot->unit_type);
                            $qty_left  = $stock_qty->subtract($req_qty);
                            $stock_dec = $qty_left->toUnit($inventory->unit_type);
                        }
                        elseif($inventory->physical_quantity == 'Volume')
                        {
                            $stock_qty = new Volume($inventory->stock, $inventory->unit_type);
                            $req_qty   = new Volume(($list->quantity * $inventory->pivot->quantity), $inventory->pivot->unit_type);
                            $qty_left  = $stock_qty->subtract($req_qty);
                            $stock_dec = $qty_left->toUnit($inventory->unit_type);
                        }
                        else
                        {
                            $stock_dec = $inventory->stock - $list->quantity;
                        }

                        $inventory->stock = $stock_dec;
                        $inventory->save();
                    }
                } 
            }

            $this->notification();

            $orderList = Order::with(
                    'order_list', 'order_list.product', 'order_list.product_size'
                )->where('id', $order->id)->first();

            // $order_list = OrderList::with('product', 'product_size')->where('order_id', $order->id)->get();

            return json_encode(['status' => 'success', 'order' => $orderList]);
        }
        else
        {
            $this->notification();
        }

    	return ['status' => 'failed'];
    }
    # END SAVE FUNCTION


    public function charge_save(Request $request)
    {
        $order = Order::where('transaction_no', $request->transaction_no)
                ->first();

        $order->cash        = $request->cash;
        $order->payable     = $request->payable;
        $order->discount    = $request->discount;
        $order->change      = $request->change;
        $order->vat         = $request->vat;
        $order->total       = $request->amount_due;
        $order->status      = 'Paid';

        if($order->save()) {
            $order_list = OrderList::with('product', 'product_size', 'order')->whereOrderId($order->id)->get();

            return json_encode(['status' => 'success', 'order' => $order_list]);
        }
    }

    public function product_availability($product_id, $quantity)
    {
        $product      = Product::findOrFail($product_id);
        $product_size = $product->product_size;
        $status       = 'Not Available';
        $available    = 0;

        foreach ($product_size as $size) 
        {
            $inventories = $size->ingredients;

            foreach($inventories as $inventory)
            {
                $qty_left  = 0;
                $stock_dec = 0;

                if($inventory->physical_quantity == 'Mass')
                {
                    $stock_qty = new Mass($inventory->stock, $inventory->unit_type);
        
                    $req_qty   = new Mass(($quantity * $inventory->pivot->quantity), $inventory->pivot->unit_type);
        
                    $qty_left  = $stock_qty->subtract($req_qty);
        
                    $stock_dec = $qty_left->toUnit($inventory->unit_type);
                }
                elseif($inventory->physical_quantity == 'Volume')
                {
                    $stock_qty = new Volume($inventory->stock, $inventory->unit_type);
        
                    $req_qty   = new Volume(($quantity * $inventory->pivot->quantity), $inventory->pivot->unit_type);
        
                    $qty_left  = $stock_qty->subtract($req_qty);
        
                    $stock_dec = $qty_left->toUnit($inventory->unit_type);
                }
                else
                {
                    $stock_dec = $quantity;
                }

                if($stock_dec > 0)
                    $available++;
            }

            if(count($inventories) == $available)
                $status = 'Available';
        }

        return $status;
    }

    public function available_table()
    {
        $available = [];
        $pendings = Order::select('table_no')
                ->where('status', 'Unpaid')
                ->where('type', 'Dine-in')
                ->whereBetween('created_at', [Date('Y-m-d 00:00:00'), Date('Y-m-d 23:59:59')])
                ->get();

        $tables = Table::whereNotIn('number', $pendings)->get();
        return json_encode($tables);
    }

    public function unpaid()
    {
        $tables = Order::select('table_no')
                ->where('status', 'Unpaid')
                ->where('type', 'Dine-in')
                ->whereBetween('created_at', [Date('Y-m-d 00:00:00'), Date('Y-m-d 23:59:59')])
                ->get();

        return $tables;
    }

    public function get_order($table)
    {
        $order = Order::with(
                    'order_list', 'order_list.product', 'order_list.product_size', 'table'
            )->where('status', 'Unpaid')
            ->where('type', 'Dine-in')
            ->whereBetween('created_at', [Date('Y-m-d 00:00:00'), Date('Y-m-d 23:59:59')])
            ->where('table_no', $table)
            ->first();

        return json_encode(['order' => $order]);
    }

    public function get_order_list($transaction_no)
    {
        $order = Order::where('transaction_no', $transaction_no)->first();
        $order_list = OrderList::with(  'product', 'product_size', 'order.table')
            ->where('order_id', $order->id)
            ->get();

        return $order_list;
    }

    public function cancel_order(Request $request)
    {
        $order = Order::whereTransactionNo($request->transaction_no)->first();
        // dd($order);
        $order_list = OrderList::whereOrderId($order->id)->with('product_size')->get();

        foreach($order_list as $list)
        {
            $stock = 0;

            foreach($list->product_size->ingredients as $ingredient) {
                if (($ingredient->physical_quantity == 'Mass') || ($ingredient->physical_quantity == 'Volume')) {
                    $stock = $list->quantity * $ingredient->pivot->quantity; // Used ingredient Stock
                    $ingredient_stock = new Mass($ingredient->stock, $ingredient->unit_type); // Actual Stock
                    $overall_stock = $ingredient_stock->add(new Mass($stock, $ingredient->pivot->unit_type));
                    $ingredient->stock = $overall_stock;
                    if($ingredient->save())
                        $list->delete();
                } else {
                    $stock = $list->quantity * $ingredient->pivot->quantity;
                    $ingredient->stock = $ingredient->stock + $stock;
                    if($ingredient->save())
                        $list->delete();
                }
            }
        }

        $order = Order::where('transaction_no', $request->transaction_no)->first();

        if(count($order->order_list) == 0)
        {
            $order->status = 'Cancelled';
            $order->save();
        }

        return json_encode(['status' => $order->status]);
    }

    /*public function check_cancel_order()
    {
        $order = Order::whereTransactionNo('18-02229845')->first();
        // dd($order);
        $order_list = OrderList::whereOrderId($order->id)->with('product_size')->get();
        // dd($order_list);

        foreach($order_list as $list)
        {
            $stock = 0;

            foreach($list->product_size->ingredients as $ingredient) {
                if (($ingredient->physical_quantity == 'Mass') || ($ingredient->physical_quantity == 'Volume')) {
                    $stock = $list->quantity * $ingredient->pivot->quantity; // Used ingredient Stock
                    $ingredient_stock = new Mass($ingredient->stock, $ingredient->unit_type); // Actual Stock
                    $overall_stock = $ingredient_stock->add(new Mass($stock, $ingredient->pivot->unit_type));
                    $ingredient->stock = $overall_stock;
                    if($ingredient->save())
                        $list->delete();
                } else {
                    $stock = $list->quantity * $ingredient->pivot->quantity;
                    $ingredient->stock = $ingredient->stock + $stock;
                    if($ingredient->save())
                        $list->delete();
                }
            }
        }

        $order = Order::where('transaction_no', '18-02613449')->first();

        if(count($order->order_list) == 0)
        {
            $order->status = 'Cancelled';
            $order->save();
        }

        return json_encode(['status' => $order->status]);
    }*/

    public function notification()
    {
        $inventories = Inventory::whereRaw('stock < reorder_level')->get();

        foreach ($inventories as $inventory) {
            $name = '';
            $desc = $inventory->name.' has '.$inventory->stock.' stocks left.';

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

            Notification::updateOrCreate(
                [
                    'name' => $name,
                    'date' => date('Y-m-d'), 
                    'description' => $desc,
                    'stock_from' => 'POS',
                    'status' => 'new'
                ],
                [
                    'inventory_id' => $inventory->id
                ]
            ); 
        }
    }
}
