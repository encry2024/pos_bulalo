<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Model;
use App\Models\Notification\Traits\Relationship\NotificationRelationship;

class Notification extends Model
{
	use NotificationRelationship;
    protected $fillable = ['id', 'name', 'date', 'description', 'stock_from', 'inventory_id', 'status'];
}
