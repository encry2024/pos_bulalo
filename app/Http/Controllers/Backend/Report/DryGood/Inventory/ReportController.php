<?php

namespace App\Http\Controllers\Backend\Report\DryGood\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\DryGood\Stock\Stock;
use App\Models\DryGood\Inventory\Inventory;
use DB;

class ReportController extends Controller
{
	public function index(){
		$categories  = Category::all();
		$reports     = [];
		$index		 = 0;
		$index2		 = 0;

		$to      = date('Y-m-d', strtotime('sunday'));
        $from    = date('Y-m-d', strtotime($to.' -6 day'));

		foreach($categories as $category)
		{
			$arr = [];
			$inventories = Inventory::where('category_id', $category->id)
							->withTrashed()
							->get();

			foreach ($inventories as $inventory) 
			{
				$report   = $this->filterReport($inventory->id, $from, $to);

				$arr[$index2] = (object)[
										'name' => $inventory->name,
										'stock'=> $inventory->stock,
										'unit' => $inventory->unit_type,
										'cost' => $inventory->stocks->last()->price,
										'days' => $report
									];
				$index2++;
			}

			$reports[$index] = (object)['category' => $category->name, 'items' => $arr];
			$index++;
		}
		
		return view('backend.report.dry_good.inventory.index', compact('reports', 'from', 'to'));
	}

	public function store(Request $request){
		$categories  = Category::all();
		$reports     = [];
		$index		 = 0;
		$index2		 = 0;

		$from    	 = date('Y-m-d', strtotime($request->from));
        $to      	 = date('Y-m-d', strtotime($request->to));


		foreach($categories as $category)
		{
			$arr = [];
			$inventories = Inventory::where('category_id', $category->id)
							->withTrashed()
							->get();


			foreach ($inventories as $inventory) 
			{
				$report   = $this->filterReport($inventory->id, $from, $to);

				$arr[$index2] = (object)[
										'name' => $inventory->name,
										'stock'=> $inventory->stock,
										'unit' => $inventory->unit_type,
										'cost' => $inventory->stocks->last()->price,
										'days' => $report
									];
				$index2++;
			}

			$reports[$index] = (object)['category' => $category->name, 'items' => $arr];
			$index++;
		}
		// return $reports;
		return view('backend.report.dry_good.inventory.index', compact('reports', 'from', 'to'));
	}

	public function filterReport($inventory_id, $from, $to){
		$index	 	 = 0;
		$weekdays    = ['mon', 'tue', 'wed', 'thurs'];
		$objects     = [];
		$sundays     = $this->getSundays($from, $to);
		$sunday_days = [];

		foreach($sundays as $sunday)
		{
			$days    = [];

			for($i = 6; $i > 2; $i--)
			{
				$days[$i] = date('Y-m-d', strtotime($sunday.' -'.$i.' day'));
			}

			
			//
			// arrange date index
			//

			$temp    = $days;
			$days[0] = $temp[6];
			$days[1] = $temp[5];
			$days[2] = $temp[4];
			$days[3] = $temp[3];
			$days    = array_splice($days, 0, 4);


			for($i = 0; $i < count($days); $i++) 
			{
				$day 	 = $days[$i];
				$ing_use = 0;
				$qty_use = 0;

				$stock   = Stock::where('inventory_id', $inventory_id)
						 ->where(DB::raw('date(created_at)'), $day)
						 ->withTrashed()
						 ->first();

				$price   = count($stock) ? $stock->price : 0;
				
				$objects[$weekdays[$i]] = (object)[
						'stocks' => $stock->quantity
					];
			}
			$sunday_days[$index] = $objects;
			$index++;
		}

		return $sunday_days;
	}



	public function getSundays($from, $to){
        $sundays  = [];
        $index    = 0;
        $from_day = date('d', strtotime($from));
        $from_mon = date('m', strtotime($from));
        $from_yr  = date('Y', strtotime($from));

        $to_day   = date('d', strtotime($to));
        $to_mon   = date('m', strtotime($to));
        $to_yr    = date('Y', strtotime($to));

        if(($to_mon - $from_mon) == 0)
        {
            for($i = $from_day - 1; $i < $to_day; $i++)
            {
                $date = date('Y-m-d', strtotime($from.' +'.$i.' day'));
                
                if($this->isSunday($from, $date))
                {
                    $sundays[$index] = $date;
                    $index++;
                }
            }
        }
        elseif(($to_mon - $from_mon) > 0)
        {

            for($x = 0; $x <= ($to_mon - $from_mon); $x++)
            {
                $month = $from_yr.'-'.($from_mon + $x).'-'.$from_day;

                for($i = $from_day - 1; $i < $to_day; $i++)
                {
                    $date = date('Y-m-d', strtotime($month.' +'.$i.' day'));

                    if($this->isSunday($month, $date))
                    {
                        if(array_search($date, $sundays) == false)
                        {
                            $sundays[$index] = $date;
                            $index++;
                        }
                    }
                }

            }
        }

        return $sundays;
    }

    public function isSunday($mon, $val){

        for($i = 1; $i <= 6; $i++)
        {
            $sunday = date('Y-m-d', strtotime($mon.' +'.$i.' sunday'));

            if($val === $sunday)
            {
                return TRUE;
            }

        }

        return FALSE;
    }
}
