<?php

namespace App\Models\DryGood\GoodsReturn\Traits\Relationship;

use App\Models\DryGood\Inventory\Inventory as DryGood;
use App\Models\Inventory\Inventory as POS;

/**
 * Class RoleRelationship.
 */
trait GoodsReturnRelationship
{

	public function inventory(){
		return $this->belongsTo(DryGood::class, 'inventory_id');
	}

}