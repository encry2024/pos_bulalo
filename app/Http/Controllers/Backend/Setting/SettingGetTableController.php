<?php

namespace App\Http\Controllers\Backend\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Setting\SettingTableRepository;

class SettingGetTableController extends Controller
{
	protected $tables;

	public function __construct(SettingTableRepository $tables){
		$this->tables = $tables;
	}

    public function __invoke(){
    	return Datatables::of($this->tables->getForDataTable())
    		->escapeColumns(['id'])
    		->addColumn('actions', function($tables) {
    			return $tables->action_buttons;
    		})
    		->make(true);
    }
}
