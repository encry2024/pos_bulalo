<?php

namespace App\Http\Controllers\Backend\Commissary\Stock;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Commissary\Stock\StockRepository;
use App\Models\Commissary\Stock\Stock;
use App\Models\Commissary\Inventory\Inventory;
use Illuminate\Http\Request;

/**
 * Class UserTableController.
 */
class StockTableController extends Controller
{
    
    protected $stocks;

    public function __construct(StockRepository $stocks){
        $this->stocks = $stocks;
    }

    public function __invoke(Request $request){
        return Datatables::of($this->stocks->getForDataTable())
	        ->escapeColumns(['id', 'sort'])
            ->editColumn('name', function($stock) {
                return $stock->inventory->supplier == 'Other' ? $stock->inventory->other_inventory->name : $stock->inventory->drygood_inventory->name;
            })
        	->addColumn('actions', function($stock) {
        		return $stock->action_buttons;
        	})
            ->make(true);
    }

}
