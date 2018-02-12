<?php

namespace App\Http\Controllers\Backend\Report\DryGood\Delivery;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DryGood\Delivery\Delivery;
use App\Models\DryGood\Product\Product;
use App\Models\DryGood\Inventory\Inventory;
use App\Models\DryGood\Stock\Stock;
use App\Models\Category\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(){
        $carbon = new Carbon();
        $date = $carbon->toDateString();

        $items = Delivery::where('date', $date)->get();
        
    	return view('backend.report.dry_good.delivery.index', compact('items', 'date'));
    }

    public function store(Request $request){
        $carbon = new Carbon($request->date);
        $date = $carbon->toDateString();

        $items = Delivery::where('date', $date)->get();
        
        return view('backend.report.dry_good.delivery.index', compact('items', 'date'));
    }
}
