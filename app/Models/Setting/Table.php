<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;
use App\Models\Setting\TableTraits\Attribute\TableAttribute;

class Table extends Model
{
	use TableAttribute;

    protected $table = 'tables';
    protected $fillable = ['number', 'price'];
}
