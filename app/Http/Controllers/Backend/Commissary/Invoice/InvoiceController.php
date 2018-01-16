<?php

namespace App\Http\Controllers\Backend\Commissary\Invoice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commissary\Stock\Stock;
use App\Models\Commissary\Inventory\Inventory;
use Carbon\Carbon;

class InvoiceController extends Controller
{
	public function index(){
		$date = Date("m/d/Y");
		$carbon = new Carbon($date);

		$datas = $this->fetchRecord($carbon->format('Y-m-d'));

		return view('backend.commissary.invoice.index', compact('date', 'datas'));
	}

	public function store(Request $request){
		$date =  $request->date;
		$carbon = new Carbon($request->date);

		$datas = $this->fetchRecord($carbon->format('Y-m-d'));
		
		return view('backend.commissary.invoice.index', compact('date', 'datas'));
	}

	public function fetchRecord($date) {
		$inventory = Inventory::with(
					[
						'stocks' => function($q) use ($date) 
						{
							$q->selectRaw('sum(quantity) as quantity, inventory_id')
								->where('received', $date)
								->withTrashed()
								->groupBy('inventory_id');
						}
					])
					->whereHas('stocks', function($q) use ($date) 
						{
							$q->selectRaw('sum(quantity) as quantity, inventory_id')
								->where('received', $date)
								->withTrashed()
								->groupBy('inventory_id');
						})
					->get();

		return $inventory;
	}
}
