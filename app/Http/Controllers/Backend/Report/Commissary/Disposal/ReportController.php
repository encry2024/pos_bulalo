<?php

namespace App\Http\Controllers\Backend\Report\Commissary\Disposal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commissary\Dispose\Dispose;

class ReportController extends Controller
{
    public function index(){
    	$disposals = Dispose::with(['inventory' => function($q) {
			    		$q->withTrashed();
			    	}])
    				->where('date', date('Y-m-d'))
    				->withTrashed()
    				->get();

    	return view('backend.report.commissary.disposal.index', compact('disposals'));
    }

    public function store(Request $request){
    	$disposals = Dispose::with(['inventory' => function($q) {
			    		$q->withTrashed();
			    	}])
    				->where('date', $request->date)
			    	->withTrashed()
			    	->get();

    	return view('backend.report.commissary.disposal.index', compact('disposals'));
    }
}
