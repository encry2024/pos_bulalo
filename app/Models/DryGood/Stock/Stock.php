<?php

namespace App\Models\DryGood\Stock;

use Illuminate\Database\Eloquent\Model;
use App\Models\DryGood\Stock\Traits\Attribute\StockAttribute;
use App\Models\DryGood\Stock\Traits\Relationship\StockRelationship;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
	use StockAttribute, StockRelationship, SoftDeletes;

    protected $table = 'drygood_stocks';

    protected $fillable = ['id', 'quantity', 'price', 'received', 'expiration', 'status', 'inventory_id'];

    protected $dates = ['deleted_at'];
}
