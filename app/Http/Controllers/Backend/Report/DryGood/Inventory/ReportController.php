<?php

namespace App\Http\Controllers\Backend\Report\DryGood\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\DryGood\Stock\Stock;
use App\Models\DryGood\Inventory\Inventory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
	public function index(){
		$carbon = new Carbon();
		$date = $carbon->toDateString();

		$items = Stock::where('received', $date)->get();
		
		return view('backend.report.dry_good.inventory.index', compact('items', 'date'));
	}

	public function store(Request $request){
		$carbon = new Carbon($request->date);
		$date = $carbon->toDateString();

		$items = Stock::where('received', $date)->get();
		
		return view('backend.report.dry_good.inventory.index', compact('items', 'date'));
	}
}
