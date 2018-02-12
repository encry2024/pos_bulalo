<?php

namespace App\Http\Controllers\Backend\Report\Commissary\Delivery;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Commissary\Delivery\Delivery;
use App\Models\Commissary\Product\Product;
use App\Models\Commissary\Inventory\Inventory;
use App\Models\Commissary\Stock\Stock;
use App\Models\Category\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(){
        $carbon = new Carbon();
        $date = $carbon->toDateString();

        $items = Delivery::where('date', $date)->get();
        
    	return view('backend.report.commissary.delivery.index', compact('items', 'date'));
    }

    public function store(Request $request){
        $carbon = new Carbon($request->date);
        $date = $carbon->toDateString();

        $items = Delivery::where('date', $date)->get();
        
        return view('backend.report.commissary.delivery.index', compact('items', 'date'));
    }
}
