<?php

namespace App\Models\Commissary\Inventory;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commissary\Inventory\Traits\Relationship\InventoryRelationship;
use App\Models\Commissary\Inventory\Traits\Attribute\InventoryAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
	use InventoryAttribute, InventoryRelationship, SoftDeletes;
	protected $table = 'commissary_inventories';

    protected $fillable = ['id', 'inventory_id', 'stock', 'reorder_level', 'unit_type', 'category_id', 'physical_quantity'];

    protected $dates = ['deleted_at'];
}
