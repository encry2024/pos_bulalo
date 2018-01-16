<?php

namespace App\Models\Commissary\Dispose;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commissary\Dispose\Traits\Relationship\DisposeRelationship;
use App\Models\Commissary\Dispose\Traits\Attribute\DisposeAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispose extends Model
{
	use DisposeAttribute, DisposeRelationship, SoftDeletes;

    protected $fillable = ['inventory_id', 'date', 'quantity', 'cost', 'total_cost', 'reason', 'witness'];

    protected $dates = ['deleted_at'];
}
