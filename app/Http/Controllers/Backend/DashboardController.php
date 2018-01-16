<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\OrderList\OrderList;
use App\Models\Product\Product;
use App\Models\Notification\Notification;
use App\Models\Request\RequestMessage;
use App\Models\Access\User\User;
use App\Models\Response\ResponseMessage;
use App\Models\Commissary\Product\Product as CommissaryProduct;
use App\Models\Commissary\Inventory\Inventory as CommissaryInventory;
use App\Models\Inventory\Inventory;
use DB;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
    	$months 	= [];
    	$monthNames = [];
    	$tops 		= [];
        $index      = 0;
        $requests   = RequestMessage::orderBy('id', 'desc')->take(5)->get();

    	for($i = 1; $i <= 12; $i++){
    		$date = strlen($i) > 1 ? $i.'/01/2017' : '0'.$i.'/01/2017';

    		$str = date('M', strtotime($date));
    		$monthNames[$i -1 ] = $str;
    	}

    	for($i = 1; $i <= 12; $i++){
    		$i = $i < 10 ? '0'.$i : $i;
    		$total = Order::selectRaw('sum(payable) as "total"')
    				->whereBetween('created_at', [date('Y-'.$i.'-01'), date('Y-'.$i.'-31')])
                    ->where('status', 'Paid')
    				->first()->total;

    	 	$total = $total > 0 ? $total : 0;
    	 	$months[$i - 1] = $total;
    	}

        $p_order = Order::with(['order_list'])->get();

        foreach ($p_order as $p) 
        {
            $count = 0;
            foreach ($p->order_list as $list) 
            {
                $tops[$index] = ['product_id' => $list->product_id, 'quantity' => $list->quantity];
                $index++;
            }
        }

        $topProducts = array();
        foreach ($tops as $val) {
            $product = Product::findOrFail($val['product_id']);
            $flag = array_search($product->name, $topProducts);

            if(!empty($topProducts)){
                if(!empty($topProducts[$product->name])) {
                    $topProducts[$product->name] += $val['quantity'];
                }
                else {
                    $topProducts[$product->name] = $val['quantity'];
                }
            }
            else {
                $topProducts[$product->name] = $val['quantity'];
            }
        }


        $tops  = array();
        $index = 0;
        foreach($topProducts as $key1 => $value1) {
            foreach ($topProducts as $key2 => $value2) {
                if($value1 < $value2){
                    
                }
            }
        }

		// $temp = 0;

		// for($i=0; $i < count($tops); $i++)
		// {  
		// 	for($j=1; $j < (count($tops) - $i); $j++)
		// 	{  
		// 		if($tops[$j-1]->count < $tops[$j]->count)
		// 		{  
		// 			//swap elements  
		// 			$temp = $tops[$j-1];  
		// 			$tops[$j-1] = $tops[$j];  
		// 			$tops[$j] = $temp;  
		// 		}  
		// 	}  
		// }  

        $products     = $this->fetchCommissaryProduct();
        $inventories  = $this->fetchCommissaryInventory();
        $commissaries = [];
        
        for($i=1; $i <= count($products); $i++)
        {
            $commissaries[$i] = $products[$i] + $inventories[$i];
        }


        return view('backend.dashboard', compact('months', 'monthNames', 'topProducts', 'requests', 'commissaries'));
    }

    public function getRequest($id) {
    	$request  = RequestMessage::findOrFail($id);
    	$user     = User::findOrFail($request->user_id);
    	$response = $request->response;

    	return [$request, $user, $response];
    }

    public function getAllRequest(){
    	$requests = RequestMessage::with('user')->orderBy('id', 'desc')->take(50)->get();

    	return $requests;
    }

    public function storeResponse(Request $request)
    {
    	ResponseMessage::updateOrCreate(
    		['request_id' => $request->request_id],
    		[
    			'message' => $request->message,
    			'status'  => $request->status
    		]
    	);

    	return 'success';
    }


    public function fetchCommissaryProduct(){
        $months     = [];
        $monthNames = [];
        $commissary = CommissaryProduct::select('id')->withTrashed()->get();
        $inventory  = Inventory::select('id')->whereIn('inventory_id', $commissary)->where('supplier', 'Commissary Product')->withTrashed()->get();

        for($i = 1; $i <= 12; $i++)
        {
            $date = strlen($i) > 1 ? $i.'/01/'.date('Y') : '0'.$i.'/01/'.date('Y');

            $str = date('M', strtotime($date));
            $monthNames[$i -1 ] = $str;
        }
    
 
        for($i = 1; $i <= 12; $i++)
        {
            $total      = null;

            $from = date('Y-'.$i.'-01');
            $to   = date('Y-'.$i.'-31');

            $orders = Order::with('order_list.product_size')
                    ->whereBetween(DB::raw('date(created_at)'), [$from, $to])
                    ->where('status', 'Paid')
                    ->get();

            if(count($orders))
            {
                foreach ($orders as $order) 
                {
                    $qty   = 0;
                    $lists = $order->order_list;

                    foreach ($lists as $list) 
                    {
                        $size         = $list->product_size->size;
                        $product      = $list->product;
                        $product_size = $product->product_size->where('size', $size)->first();
                        $total        = $total + ($list->quantity * $product_size->cost);
                    } 
                }
            }

            $months[$i] = number_format($total, 2);
        }

        return $months;
    }

    public function fetchCommissaryInventory(){
        $months     = [];
        $monthNames = [];
        $commissary = Inventory::select('id')->withTrashed()->get();
        $inventory  = Inventory::select('id')->whereIn('inventory_id', $commissary)->where('supplier', 'Commissary Raw Material')->withTrashed()->get();

        for($i = 1; $i <= 12; $i++)
        {
            $date = strlen($i) > 1 ? $i.'/01/'.date('Y') : '0'.$i.'/01/'.date('Y');

            $str = date('M', strtotime($date));
            $monthNames[$i -1 ] = $str;
        }
    
 
        for($i = 1; $i <= 12; $i++)
        {
            $total      = null;

            $from = date('Y-'.$i.'-01');
            $to   = date('Y-'.$i.'-31');

            $orders = Order::with(
                        [
                            'order_list.product_size',
                            'order_list.product.product_size.ingredients' => function($q) use ($inventory) {
                                $q->whereIn('inventory_product_size.inventory_id', $inventory)->withTrashed();
                            }
                        ])
                    ->whereHas('order_list.product.product_size.ingredients', function($q) use($inventory) {
                        $q->whereIn('inventory_product_size.inventory_id', $inventory)->withTrashed();
                    })
                    ->whereBetween(DB::raw('date(created_at)'), [$from, $to])
                    ->where('status', 'Paid')
                    ->get();

            if(count($orders))
            {
                foreach ($orders as $order) 
                {
                    $qty   = 0;
                    $lists = $order->order_list;

                    foreach ($lists as $list) 
                    {
                        $size         = $list->product_size->size;
                        $product      = $list->product;
                        $product_size = $product->product_size->where('size', $size)->first();

                        if(count($product_size))
                        {
                           $ingredients  = $product_size->ingredients;

                            foreach ($ingredients as $ingredient) {
                                $price = 0;

                                if(count($ingredient->stocks))
                                    $price = ($ingredient->stocks->last()->price / $ingredient->stocks->last()->quantity);

                                $qty       = (int)$list->quantity;
                                $qty_use   = $ingredient->pivot->quantity;
                                $total     = $total + (($qty * $qty_use) * $price);
                            } 
                        }
                    } 
                }
            }

            $months[$i] = number_format($total, 2);
        }

        return $months;
    }
}
