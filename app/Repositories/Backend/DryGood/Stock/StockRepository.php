<?php

namespace App\Repositories\Backend\DryGood\Stock;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\DryGood\Stock\Stock;

class StockRepository extends BaseRepository
{
	const MODEL = Stock::class;

	public function getForDataTable()
    {
		return $this->query()
            ->leftJoin('drygood_inventories',
                'drygood_stocks.inventory_id', '=', 'drygood_inventories.id'
            )
            ->with(['inventory' => function($q) {
                $q->withTrashed();
            }])->select('drygood_stocks.id',
                'drygood_stocks.quantity',
                'drygood_stocks.price',
                'drygood_stocks.received',
                'drygood_stocks.expiration',
                'drygood_stocks.status',
                'drygood_stocks.inventory_id',
                'drygood_inventories.name');
	}
}