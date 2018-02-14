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
            ->when('inventory.supplier' == 'DryGoods Material', function($q) {
                $q->with('inventory.dry_good_inventory');
            })
            ->when('inventory.supplier' === 'Commissary Product', function($q) {
                $q->with('commissary_product');
            })
            ->when('inventory.supplier' == 'DryGoods Material', function($q) {
                $q->with('inventory.dry_good_inventory');
            })->get();
	}
}