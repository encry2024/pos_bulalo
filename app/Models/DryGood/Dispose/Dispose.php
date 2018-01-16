<?php

namespace App\Models\DryGood\Dispose;

use Illuminate\Database\Eloquent\Model;
use App\Models\DryGood\Dispose\Traits\Relationship\DisposeRelationship;
use App\Models\DryGood\Dispose\Traits\Attribute\DisposeAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispose extends Model
{
	use DisposeAttribute, DisposeRelationship, SoftDeletes;

	protected $table = 'drygood_disposes';

    protected $fillable = ['inventory_id', 'date', 'quantity', 'cost', 'total_cost', 'reason', 'witness'];

    protected $dates = ['deleted_at'];
}
