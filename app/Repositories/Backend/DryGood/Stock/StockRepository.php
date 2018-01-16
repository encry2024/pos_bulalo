<?php

namespace App\Repositories\Backend\DryGood\Stock;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\DryGood\Stock\Stock;

class StockRepository extends BaseRepository
{
	const MODEL = Stock::class;

	public function getForDataTable(){
		return $this->query()
				->with(['inventory' => function($q) {
					$q->withTrashed();
				}])
				->select('id', 'quantity', 'price', 'received', 'expiration', 'status', 'inventory_id');
	}
}