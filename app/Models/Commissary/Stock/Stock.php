<?php

namespace App\Models\Commissary\Stock;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commissary\Stock\Traits\Attribute\StockAttribute;
use App\Models\Commissary\Stock\Traits\Relationship\StockRelationship;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
	use StockAttribute, StockRelationship, SoftDeletes;

    protected $table = 'commissary_stocks';

    protected $fillable = ['id', 'quantity', 'price', 'received', 'expiration', 'status', 'inventory_id'];

    protected $dates = ['deleted_at'];

}
