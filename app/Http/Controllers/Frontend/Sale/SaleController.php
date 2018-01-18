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
    public function index(){
        $total  = 0;
        $orders = Order::where(Db::raw('date(created_at)'), date('Y-m-d'))
                  ->where('user_id', Auth::user()->id)
                  ->where('status', '!=', 'Unpaid')
                  ->orderBy('created_at', 'desc')
                  ->get();

        foreach ($orders as $order) {
            $total = $total + $order->payable;
        }

        return view('frontend.user.sale.daily', compact('total', 'orders'));
    }

    public function monthly(){
        $total  = 0;
        $date   = date('F Y');
        $orders = Order::whereBetween(Db::raw('date(created_at)'), [date('Y-m-01'), date('Y-m-31')])
                  ->where('user_id', Auth::user()->id)
                  ->where('status', '!=', 'Unpaid')
                  ->orderBy('created_at', 'desc')
                  ->get();

        foreach ($orders as $order) {
            $total = $total + $order->payable;
        }

        return view('frontend.user.sale.monthly', compact('total', 'orders', 'date'));
    }

    public function save(Request $request){
    	$arr 	 = json_decode($request->orders);
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
                    'payable'   => $request->payable,
                    'discount'  => $request->discount,
                    'vat'       => $request->vat,
                    'charge'    => $request->charge,
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
                $size = ProductSize::where('size', $arr[$i]->size)->first();

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

            $order = Order::with(
                    'order_list', 'order_list.product', 'order_list.product_size'
                )->where('id', $order->id)->first();

            return json_encode(['status' => 'success', 'order' => $order]);
        }
        else
        {
            $this->notification();
        }

    	return ['status' => 'failed'];
    }

    public function charge_save(Request $request) {
        $order = Order::with('order_list', 'order_list.product', 'order_list.product_size')
                ->where('transaction_no', $request->transaction_no)
                ->first();

        $order->cash        = $request->cash;
        $order->payable     = $request->payable;
        $order->discount    = $request->discount;
        $order->change      = $request->change;
        $order->vat         = $request->vat;
        $order->charge      = $request->charge;
        $order->total       = $request->amount_due;
        $order->status      = 'Paid';
        $order->save();

        return json_encode(['status' => 'success', 'order' => $order]);
    }

    public function product_availability($product_id, $quantity){
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

    public function available_table(){
        $available = [];
        $total_table = Table::first()->count;

        $pendings = Order::select('table_no')
                ->where('status', 'Unpaid')
                ->where('type', 'Dine-in')
                ->whereBetween('created_at', [Date('Y-m-d 00:00:00'), Date('Y-m-d 23:59:59')])
                ->get();

        for($i = 0; $i < $total_table; $i++)
        {
            $available[$i] = $i + 1;
        }

        foreach ($pendings as $pending) 
        {
            $index = array_search($pending->table_no, $available);
            array_splice($available, $index, 1);
        }
        return $available;
    }

    public function unpaid(){
        $tables = Order::select('table_no')
                ->where('status', 'Unpaid')
                ->where('type', 'Dine-in')
                ->whereBetween('created_at', [Date('Y-m-d 00:00:00'), Date('Y-m-d 23:59:59')])
                ->get();

        return $tables;
    }

    public function get_order($table) {
        $order = Order::with(
                    'order_list', 'order_list.product', 'order_list.product_size'
                )->where('status', 'Unpaid')
                ->where('type', 'Dine-in')
                ->whereBetween('created_at', [Date('Y-m-d 00:00:00'), Date('Y-m-d 23:59:59')])
                ->where('table_no', $table)
                ->first();

        return json_encode(['order' => $order]);
    }

    public function get_order_list($transaction_no) {
        $order = Order::with('order_list', 'order_list.product', 'order_list.product_size')
                ->where('transaction_no', $transaction_no)
                ->first();

        return $order;
    }

    public function cancel_order($transaction_no) {
        $order = Order::with('order_list', 'order_list.product_size')
            ->where('transaction_no', $transaction_no)
            ->first();

        foreach($order->order_list as $list)
        {
            $stock = 0;
            foreach($list->product_size->ingredients as $ingredient)
            {
                $stock = $list->quantity * $ingredient->pivot->quantity;
                $ingredient->stock = $ingredient->stock + $stock;
                $ingredient->save();

                $list->status = 'Cancelled';
                $list->save();
            }
        }

        $order->status = 'Cancelled';
        $order->save();

        return json_encode(['status' => $order->status]);
    }

    public function notification(){
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
