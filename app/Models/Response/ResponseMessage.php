<?php

namespace App\Models\Response;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResponseMessage extends Model
{
	protected $table = 'responses';
    protected $fillable = ['id', 'request_id', 'message', 'status'];
}
