<?php

namespace App\Repositories\Backend\DryGood\Inventory;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\DryGood\Inventory\Inventory;

class InventoryRepository extends BaseRepository
{
	const MODEL = Inventory::class;

	public function getForDataTable(){
		return $this->query()
				->with('category');
	}
}