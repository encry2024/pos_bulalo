<?php

namespace App\Models\ProductSize;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductSize\Traits\Relationship\ProductSizeRelationship;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSize extends Model
{
	use ProductSizeRelationship;
	
	protected $fillable = ['id', 'product_id', 'price', 'cost', 'size'];
}
