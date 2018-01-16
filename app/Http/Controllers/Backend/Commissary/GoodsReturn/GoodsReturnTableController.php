<?php

namespace App\Http\Controllers\Backend\Commissary\GoodsReturn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Commissary\GoodsReturn\GoodsReturnRepository;
use App\Models\Commissary\GoodsReturn\GoodsReturn;
use App\Models\Commissary\Inventory\Inventory;

class GoodsReturnTableController extends Controller
{
    
	protected $goods_returns;

	public function __construct(GoodsReturnRepository $goods_returns){
		$this->goods_returns = $goods_returns;
	}


	public function __invoke(Request $request){
		return Datatables::of($this->goods_returns->getForDataTable())
			->escapeColumns('id', 'sort')
			->addColumn('name', function($goods_returns) {
				$inventory = $goods_returns->inventory;

				if($inventory->supplier == 'Other')
				{
					return $inventory->other_inventory->name;
				}

				return $inventory->drygood_inventory->name;
			})
			->addColumn('actions', function($goods_returns) {
				return $goods_returns->action_buttons;
			})
			->make(true);
	}

}
