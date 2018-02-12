<?php

namespace App\Repositories\Backend\Commissary\Stock;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Commissary\Stock\Stock;

class StockRepository extends BaseRepository
{
	const MODEL = Stock::class;

	public function getForDataTable()
    {
        return $this->query()
            ->leftJoin('drygood_inventories', function($join) {
                $join->on('commissary_stocks.inventory_id', '=', 'drygood_inventories.id');
            })
            ->leftJoin('commissary_other_inventories', function($join) {
                $join->on('commissary_stocks.inventory_id', '=', 'commissary_other_inventories.id');
            })
            ->get();
	}
}