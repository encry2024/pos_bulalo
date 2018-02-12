<?php

namespace App\Repositories\Backend\Commissary\Inventory;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Commissary\Inventory\Inventory;

class InventoryRepository extends BaseRepository
{
	const MODEL = Inventory::class;

	public function getForDataTable()
    {
        return $this->query()
            ->with('category')
            ->leftJoin('commissary_other_inventories',
                'commissary_inventories.inventory_id', '=', 'commissary_other_inventories.id'
            )
            ->leftJoin('drygood_inventories',
                'commissary_inventories.inventory_id', '=', 'drygood_inventories.id'
            )->select('commissary_inventories.*',
                DB::raw('commissary_inventories.stock           as comm_stock'),
                DB::raw('commissary_inventories.reorder_level   as comm_inv_reorder_level'),
                'commissary_other_inventories.*',
                'drygood_inventories.*')
            ->get();
	}
}