<?php

namespace App\Models\DryGood\Inventory\Traits\Relationship;

use App\Models\Category\Category;
use App\Models\DryGood\Product\Product;
use App\Models\DryGood\Inventory\Inventory;
use App\Models\DryGood\Stock\Stock;

/**
 * Class RoleRelationship.
 */
trait InventoryRelationship
{

	public function category(){
		return $this->belongsTo(Category::class);
	}

	public function stocks(){
		return $this->hasMany(Stock::class)->withTrashed();
	}

	public function products(){
		return $this->belongsToMany(Product::class, 'drygood_inventory_product', 'inventory_id', 'product_id');
	}
}