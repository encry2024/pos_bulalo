<?php

namespace App\Models\Other;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    protected $table = 'other_inventories';

    protected $fillable = ['name'];
}
