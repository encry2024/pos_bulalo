<?php

namespace App\Repositories\Backend\DryGood\Delivery;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\DryGood\Delivery\Delivery;

class DeliveryRepository extends BaseRepository
{
	const MODEL = Delivery::class;

	public function getForDataTable(){
		return $this->query()
				->with(['inventory' => function($q) {
					$q->withTrashed();
				}])
				->select('id', 'quantity', 'price', 'date', 'item_id', 'status', 'deliver_to');
	}
}