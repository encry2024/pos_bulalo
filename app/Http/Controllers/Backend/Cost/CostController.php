<?php

namespace App\Http\Controllers\Backend\Cost;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\Stock;

class CostController extends Controller
{
    public function index() {
    	$from = date('Y-m-d');
    	$to   = date('Y-m-d');

    	$stocks = Stock::whereBetween('received', [$from, $to])
    				->withTrashed()
    				->get();

    	return view('backend.cost.index', compact('from', 'to', 'stocks'));
    }

    public function store(Request $request) {
    	$from   = $request->from;
    	$to 	= $request->to;

    	$stocks = Stock::whereBetween('received', [$from, $to])
    				->withTrashed()
    				->get();

    	return view('backend.cost.index', compact('from', 'to', 'stocks'));
    }
}
