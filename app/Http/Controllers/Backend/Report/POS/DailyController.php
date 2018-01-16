<?php

namespace App\Http\Controllers\Backend\Report\POS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\OrderList\OrderList;
use App\Models\Product\Product;
use Carbon\Carbon;
use DB;


class DailyController extends Controller
{

    public $times = [
            '07:00 AM - 07:30 AM',
            '07:31 AM - 08:00 AM',
            '08:01 AM - 08:30 AM',
            '08:31 AM - 09:00 AM',
            '09:01 AM - 09:30 AM',
            '09:31 AM - 10:00 AM',
            '10:01 AM - 10:30 AM',
            '10:31 AM - 11:00 AM',
            '11:01 AM - 11:30 AM',
            '11:31 AM - 12:00 PM',
            '12:01 PM - 12:30 PM',
            '12:31 PM - 01:00 PM',
            '01:01 PM - 01:30 PM',
            '01:31 PM - 02:00 PM',
            '02:01 PM - 02:30 PM',
            '02:31 PM - 03:00 PM',
            '03:01 PM - 03:30 PM',
            '03:31 PM - 04:00 PM',
            '04:01 PM - 04:30 PM',
            '04:31 PM - 05:00 PM',
            '05:01 PM - 05:30 PM',
            '05:31 PM - 06:00 PM',
            '06:01 PM - 06:30 PM',
            '06:31 PM - 07:00 PM'
        ];


    public function index(){
        $relations = $this->fetchReport(date('Y-m-d'));
        
    	return view('backend.report.pos.daily.index', $relations);
    }

    public function store(Request $request){
        $date = date('Y-m-d', strtotime(new Carbon($request->date)));

        $relations = $this->fetchReport($date);

    	return view('backend.report.pos.daily.index', $relations);
    }


    public function fetchReport($date){
        //
        // juice
        //
        $juices         = $this->soldJuice('JUICE', $date);
        $lychee_juices  = $this->soldJuice('LYCHEE JUICE', $date);


        $relations = [
            'date'          => $date,
            'times'         => $this->times,
            'juices'        => $juices,
            'lychee_juices' => $lychee_juices,
            'shakes'        => $this->filterReport($date, 'SHAKES'),
            'desserts'      => $this->filterReport($date, 'DESSERTS'),
            'extras'        => $this->filterReport($date, 'EXTRAS'),
            'barista'       => $this->getBaristas($date)
        ];

        return $relations;
    }


    public function getBaristas($date){
        $morning    = '';
        $afternoon  = '';
        $from_am    = date('Y-m-d H:i:s A', strtotime($date.' 06:00:00'));
        $to_am      = date('Y-m-d H:i:s A', strtotime($date.' 11:59:59'));
        $from_pm    = date('Y-m-d H:i:s A', strtotime($date.' 12:00:00'));
        $to_pm      = date('Y-m-d H:i:s A', strtotime($date.' 19:59:59'));

        $barista_am = Order::whereBetween('created_at', [$from_am, $to_am])->first();
        $barista_pm = Order::whereBetween('created_at', [$from_pm, $to_pm])->first();

        if(count($barista_am))
        {
            $morning = $barista_am->user->name;
        }

        if(count($barista_pm))
        {
            $afternoon = $barista_pm->user->name;
        }

        return (object)['morning' => $morning, 'afternoon' => $afternoon];
    }
    //
    // count sold juice
    //
    public function soldJuice($category, $date) {
    	$consume_times 	= [];

    	for($i = 0; $i < count($this->times); $i++)
    	{
	    	$juice_md 		= 0;
	    	$juice_lg 		= 0;
    		$index 			= strpos($this->times[$i], '-');
	    	$from  			= date('H:i', strtotime(substr($this->times[$i], 0, $index)) );
	    	$to    			= date('H:i', strtotime(substr($this->times[$i], ($index + 1))));

	    	$products_juice = Product::with(['order_list' => function($q) use($from, $to, $date) {
	    		$q->whereRaw('time(created_at) between "'.$from.'" and "'.$to.'"')
	    		  ->whereRaw('date(created_at) between "'.$date.'" and "'.$date.'"');
	    	}])
	    	->whereHas('order_list', function($q) use($from, $to, $date) {
	    		$q->whereRaw('time(created_at) between "'.$from.'" and "'.$to.'"')
	    		  ->whereRaw('date(created_at) between "'.$date.'" and "'.$date.'"');
	    	})
	    	->where('category', $category)->get();

	    	foreach ($products_juice as $products) {
	    		foreach ($products->order_list as $product) {
	    			if($product->size == 'Medium')
	    			{
	    				$juice_md += $product->quantity;
	    			}

	    			if($product->size == 'Large')
	    			{
	    				$juice_lg += $product->quantity;
	    			}
	    		} //end foreach
	    	}//end foreach

	    	$consume 			= (object)['time' => $this->times[$i], 'size' => (object)['medium' => $juice_md, 'large' => $juice_lg]];
	    	$consume_times[$i] 	= $consume;
    	}//end for loop

    	return $consume_times;
    }


    public function filterReport($date, $category) {
        $product_sales  = [];
        $sale_index     = 0;

        $products  = Product::where('Category', $category)->orderBy('name')->get();

        foreach ($products as $item) {
            $consume_times  = [];

            for($i = 0; $i < count($this->times); $i++)
            {
                $count          = 0;
                $index          = strpos($this->times[$i], '-');
                $from           = date('H:i', strtotime(substr($this->times[$i], 0, $index)) );
                $to             = date('H:i', strtotime(substr($this->times[$i], ($index + 1))));

                $products_juice =  Product::with(['order_list' => function($q) use($from, $to, $date) {
                                    $q->whereRaw('date(created_at) between "'.$date.' " and "'.$date.'"')
                                      ->whereRaw('time(created_at) between "'.$from.' " and "'.$to.'"');
                                }])
                                ->whereHas('order_list', function($q) use($from, $to, $date) {
                                    $q->whereRaw('date(created_at) between "'.$date.'" and "'.$date.'"')
                                      ->whereRaw('time(created_at) between "'.$from.' " and "'.$to.'"');
                                })
                                ->where('category', $category)
                                ->where('id', $item->id)
                                ->get();

                foreach ($products_juice as $products) {
                    foreach ($products->order_list as $product) {
                        $count += $product->quantity;
                    } //end foreach
                }//end foreach

                $consume            = (object)['time' => $this->times[$i], 'count' => $count];
                $consume_times[$i]  = $consume;
            }//end for loop

            $product_sales[$sale_index]  = (object)['name' => $item->name, 'datas' => $consume_times];
            $sale_index++;
        }

        return $product_sales;
    }
}
