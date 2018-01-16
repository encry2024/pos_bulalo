<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;

use App\Models\Stock\Traits\Relationship\StockRelationship;

use App\Repositories\Stock\StockRepository;

use App\Models\Stock\Traits\Attribute\StockAttribute;

use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
	use StockRelationship, StockAttribute, SoftDeletes; 

    protected $fillable = ['id', 'quantity', 'price','received','expiration','inventory_id'];

    protected $dates = ['deleted_at'];
}
