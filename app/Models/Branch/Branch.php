<?php

namespace App\Models\Branch;

use Illuminate\Database\Eloquent\Model;
use App\Models\Branch\Traits\Attribute\BranchAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
	use BranchAttribute;
	
	protected $fillable = ['name', 'address', 'contact'];
}
