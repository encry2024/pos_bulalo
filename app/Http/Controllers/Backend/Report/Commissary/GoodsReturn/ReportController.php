<?php

namespace App\Http\Controllers\Backend\Report\Commissary\GoodsReturn;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commissary\GoodsReturn\GoodsReturn;

class ReportController extends Controller
{
	public function index(){
    	$disposals = GoodsReturn::with(['inventory' => function($q) {
    					$q->withTrashed();
			    	}])
    				->where('date', date('Y-m-d'))
    				->withTrashed()
    				->get();

    	return view('backend.report.commissary.goods_return.index', compact('disposals'));
    }

    public function store(Request $request){
    	$disposals = GoodsReturn::with(['inventory' => function($q) {
    						$q->withTrashed();
				    	}])
				    	->where('date', $request->date)
				    	->withTrashed()
				    	->get();

    	return view('backend.report.commissary.goods_return.index', compact('disposals'));
    }
}
