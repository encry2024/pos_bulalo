<?php

namespace App\Models\Commissary\History;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    protected $table = 'commissary_history';

    protected $fillable = ['id', 'product_id', 'description'];
}
