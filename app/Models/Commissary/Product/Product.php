<?php

namespace App\Models\Commissary\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commissary\Product\Traits\Relationship\ProductRelationship;
use App\Models\Commissary\Product\Traits\Attribute\ProductAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use ProductAttribute, ProductRelationship, SoftDeletes;

    protected $table = 'commissary_products';

    protected $fillable = ['id', 'name', 'produce', 'category', 'cost'];

    protected $dates = ['deleted_at'];
}
