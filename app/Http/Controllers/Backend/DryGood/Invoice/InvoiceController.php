<?php

namespace App\Http\Controllers\Backend\DryGood\Invoice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DryGood\Stock\Stock;
use Carbon\Carbon;

class InvoiceController extends Controller
{
	public function index(){
		$date = Date("m/d/Y");
		$carbon = new Carbon($date);

		$datas = $this->fetchRecord($carbon->format('Y-m-d'));

		return view('backend.dry_good.invoice.index', compact('date', 'datas'));
	}

	public function store(Request $request){
		$date =  $request->date;
		$carbon = new Carbon($request->date);

		$datas = $this->fetchRecord($carbon->format('Y-m-d'));

		return view('backend.dry_good.invoice.index', compact('date', 'datas'));
	}

	public function fetchRecord($date) {
		$stocks = Stock::selectRaw('sum(quantity) as quantity, inventory_id')
					->with(['inventory' => function($q) {
						$q->withTrashed();
					}])
					->where('received', $date)
					->groupBy('inventory_id')
					->withTrashed()
					->get();

		return $stocks;
	}
}
