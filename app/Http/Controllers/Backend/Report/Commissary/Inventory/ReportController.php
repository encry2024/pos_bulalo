<?php

namespace App\Http\Controllers\Backend\Report\Commissary\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Commissary\Stock\Stock;
use App\Models\Commissary\Inventory\Inventory;
use App\Models\Commissary\Product\Product;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
	public function index(){
		$carbon = new Carbon();
		$date   = $carbon->toDateString();

		$stocks = Stock::where('received', $date)->get();

		return view('backend.report.commissary.inventory.index', compact('stocks', 'date'));
	}

	public function store(Request $request){
		$carbon = new Carbon($request->date);
		$date   = $carbon->toDateString();

		$stocks = Stock::where('received', $date)->get();

		return view('backend.report.commissary.inventory.index', compact('stocks', 'date'));
	}
}
