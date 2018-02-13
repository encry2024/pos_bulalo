<?php

namespace App\Repositories\Backend\Stock;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stock\Stock;

class StockRepository extends BaseRepository
{
	const MODEL = Stock::class;

	public function getForDataTable()
    {
		return $this->query()
			->with(['inventory' => function($q) {
				$q->withTrashed();
			}])
            ->leftJoin('inventories', function($join) {
                $join->on('stocks.inventory_id', '=', 'inventories.id');
            })
            ->leftJoin('commissary_products', function($join) {
                $join->on('inventories.inventory_id', '=', 'commissary_products.id');
            })
            ->leftJoin('drygood_inventories', function($join) {
                $join->on('inventories.inventory_id', '=', 'drygood_inventories.id');
            })
            ->leftJoin('commissary_other_inventories', function($join) {
                $join->on('stocks.inventory_id', '=', 'commissary_other_inventories.id');
            })->select('stocks.*',
                'drygood_inventories.*',
                'commissary_other_inventories.*')->get();
	}
}