<?php

namespace App\Http\Controllers\Backend\DryGood\GoodsReturn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\DryGood\GoodsReturn\GoodsReturnRepository;
use App\Models\DryGood\GoodsReturn\GoodsReturn;
use App\Models\DryGood\Inventory\Inventory;

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
				$com = Inventory::where('id', $goods_returns->inventory_id)->withTrashed()->first();	

				return $com->name;
			})
			->addColumn('actions', function($goods_returns) {
				return $goods_returns->action_buttons;
			})
			->make(true);
	}

}
