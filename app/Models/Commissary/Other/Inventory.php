<?php

namespace App\Models\Commissary\Other;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
	use SoftDeletes;

    protected $table = 'commissary_other_inventories';

    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];
}
