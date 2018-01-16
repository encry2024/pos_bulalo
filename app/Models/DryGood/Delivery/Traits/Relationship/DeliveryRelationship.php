<?php

namespace App\Models\DryGood\Delivery\Traits\Relationship;

use App\Models\DryGood\Product\Product;
use App\Models\DryGood\Inventory\Inventory;
use App\Models\Branch\Branch;

/**
 * Class RoleRelationship.
 */
trait DeliveryRelationship
{

	public function product(){
		return $this->belongsTo(Product::class, 'item_id');
	}

	public function inventory(){
		return $this->belongsTo(Inventory::class, 'item_id');
	}

	public function branch(){
		return $this->belongsTo(Branch::class);
	}
}