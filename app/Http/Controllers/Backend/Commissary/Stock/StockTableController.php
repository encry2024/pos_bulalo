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
            ->addColumn('name', function($stock) {
                $name = '';

                if($stock->inventory->supplier == 'Other')
                {
                    $name = $stock->inventory->other_inventory->name;
                }
                else
                {
                    $name = $stock->inventory->drygood_inventory->name;
                }

                return $name;
            })
        	->addColumn('actions', function($stock) {
        		return $stock->action_buttons;
        	})
            ->make(true);
    }

}
