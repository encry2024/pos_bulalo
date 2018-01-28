<?php

namespace App\Http\Controllers\Backend\Report\POS\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\Stock;

class ReportController extends Controller
{
    public function index(){
    	$from   	= date('Y-m-d');
        $to     	= date('Y-m-d');
        $stocks = Stock::whereBetween('received', [$from,$to])->get();

    	return view('backend.report.pos.inventory.index', compact('stocks', 'from', 'to'));
    }

    public function store(Request $request){
    	$from   	= $request->from;
        $to     	= $request->to;
        $stocks = Stock::whereBetween('received', [$from,$to])->get();

    	return view('backend.report.pos.inventory.index', compact('stocks', 'from', 'to'));
    }
}
