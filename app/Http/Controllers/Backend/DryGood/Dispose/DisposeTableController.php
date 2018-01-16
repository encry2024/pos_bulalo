<?php

namespace App\Http\Controllers\Backend\DryGood\Dispose;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\DryGood\Dispose\DisposeRepository;
use App\Models\DryGood\Dispose\Dispose;

class DisposeTableController extends Controller
{
    
	protected $disposes;

	public function __construct(DisposeRepository $disposes){
		$this->disposes = $disposes;
	}

	public function __invoke(Request $request){
		return Datatables::of($this->disposes->getForDataTable())
			->escapeColumns('id', 'sort')
			->addColumn('name', function($dispose) {
				return $dispose->inventory->name;
			})
			->addColumn('actions', function($dispose) {
				return $dispose->action_buttons;
			})
			->make(true);
	}

}
