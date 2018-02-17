<?php

namespace App\Http\Controllers\Backend\Report\POS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\OrderList\OrderList;
use App\Repositories\Backend\Report\ReportRepository;

class ReportController extends Controller
{
    public function index()
    {
        $from   = date('Y-m-d');
        $to     = date('Y-m-d');
        $time_from   = date('00:00:00');
        $time_to     = date('23:59:59');

        $orders = Order::whereBetween('created_at', [$from.' '.$time_from, $to.' '.$time_to])
                ->where('status', 'Paid')
                ->get();
                
    	return view('backend.report.pos.sale.index', compact('orders', 'from', 'to', 'time_from', 'time_to'));
    }

    public function store(Request $request){
        $from   = date('Y-m-d', strtotime($request->from));
        $to     = date('Y-m-d', strtotime($request->to));
        $time_from   = date('H:i:s', strtotime($request->time_from));
        $time_to     = date('H:i:s', strtotime($request->time_to));

        $orders = Order::whereBetween('created_at', [$from.' '.$time_from, $to.' '.$time_to])
                    ->where('status', 'Paid')->get();

        return view('backend.report.pos.sale.index', compact('orders', 'from', 'to', 'time_from', 'time_to'));
    }

    public function show($id){
    	$order = Order::findOrFail($id);

    	return view('backend.report.pos.sale.show', compact('order'));
    }

    public function destroy($id){
    	$order = Order::findOrFail($id);

    	foreach ($order->order_list as $list) {
    		$list->delete();
    	}

    	$order->delete();

    	return redirect()->route('admin.report.pos.sale.index');
    }
}
