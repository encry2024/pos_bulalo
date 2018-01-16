<?php

namespace App\Models\History;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistoryType
 * package App.
 */
class HistoryType extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'history_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
