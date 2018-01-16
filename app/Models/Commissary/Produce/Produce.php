<?php

namespace App\Models\Commissary\Produce;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commissary\Produce\Traits\Attribute\ProduceAttribute;
use App\Models\Commissary\Produce\Traits\Relationship\ProduceRelationship;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produce extends Model
{
	use ProduceAttribute, ProduceRelationship, SoftDeletes;
	
    protected $table = 'commissary_produce';

    protected $fillable = ['id', 'product_id', 'quantity', 'date'];

    protected $dates = ['deleted_at'];
}
