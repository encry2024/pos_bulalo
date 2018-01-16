<?php

namespace App\Models\DryGood\Inventory;

use Illuminate\Database\Eloquent\Model;
use App\Models\DryGood\Inventory\Traits\Relationship\InventoryRelationship;
use App\Models\DryGood\Inventory\Traits\Attribute\InventoryAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
	use InventoryAttribute, InventoryRelationship, SoftDeletes;
	protected $table = 'drygood_inventories';

    protected $fillable = ['id', 'name', 'stock', 'reorder_level', 'unit_type', 'category_id', 'physical_quantity'];

    protected $dates = ['deleted_at'];
}
