<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Traits\Attribute\InventoryAttribute;
use App\Models\Inventory\Traits\Relationship\InventoryRelationship;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
	use InventoryAttribute, InventoryRelationship, SoftDeletes;

    protected $fillable = ['id', 'inventory_id', 'reorder_level', 'unit_type', 'category_id', 'supplier', 'physical_quantity'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $dates = ['deleted_at'];
}
