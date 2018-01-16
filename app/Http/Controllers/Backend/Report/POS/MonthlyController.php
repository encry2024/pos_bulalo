<?php

namespace App\Http\Controllers\Backend\Report\POS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\OrderList\OrderList;
use App\Models\Product\Product;
use Carbon\Carbon;
use DB;


class MonthlyController extends Controller
{
    public function index(){
        $date = date('m/Y');
        $orders = Order::whereBetween('created_at', [date('Y-m-d'), date('Y-m-d')])
                ->where('status', 'Paid')
                ->get();

    	return view('backend.report.pos.monthly.index', compact('orders', 'date'));
    }

    public function store(Request $request){
        $date = date('m/Y');
        $orders = Order::whereBetween('created_at', [$date, $date])
                ->where('status', 'Paid')
                ->get();

    	return view('backend.report.pos.monthly.index', compact('orders', 'date'));
    }
}
