<?php

namespace App\Http\Controllers\Backend\DryGood\Delivery;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\DryGood\Delivery\DeliveryRepository;
use App\Models\DryGood\Delivery\Delivery;
use App\Models\DryGood\Inventory\Inventory;

class DeliveryTableController extends Controller
{
    
	protected $deliveries;

	public function __construct(DeliveryRepository $deliveries){
		$this->deliveries = $deliveries;
	}


	public function __invoke(Request $request){
		return Datatables::of($this->deliveries->getForDataTable())
			->escapeColumns('id', 'sort')
			->addColumn('item', function($deliveries) {
				return $deliveries->inventory->name;
			})
			->addColumn('total', function($deliveries) {
				$total = $deliveries->quantity * $deliveries->price;

				return number_format($total, 2);
			})
			->addColumn('actions', function($deliveries) {
				return $deliveries->action_buttons;
			})
			->make(true);
	}

}
