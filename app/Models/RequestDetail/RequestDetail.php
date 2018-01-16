<?php

namespace App\Models\RequestDetail;

use Illuminate\Database\Eloquent\Model;
use App\Models\RequestDetail\Traits\Relationship\RequestDetailRelationship;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestDetail extends Model
{
	use RequestDetailRelationship;
	
    protected $fillable = ['id', 'request_id', 'quantity', 'unit_type'];
}
