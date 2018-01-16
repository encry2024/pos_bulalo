<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category\Traits\Attribute\CategoryAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use CategoryAttribute, SoftDeletes;

    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];
}
