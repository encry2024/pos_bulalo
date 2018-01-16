<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

use App\Models\Product\Traits\Attribute\ProductAttribute;
use App\Models\Product\Traits\Relationship\ProductRelationship;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use ProductAttribute, ProductRelationship, SoftDeletes;

    protected $fillable = ['id', 'code', 'name', 'image', 'category'];

    protected $dates = ['deleted_at'];
}
