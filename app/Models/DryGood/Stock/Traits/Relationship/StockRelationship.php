<?php

namespace App\Models\DryGood\Stock\Traits\Relationship;

use App\Models\DryGood\Inventory\Inventory;

trait StockRelationship
{
	public function inventory(){
		return $this->belongsTo(Inventory::class)->withTrashed();
	}
}