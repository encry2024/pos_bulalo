<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order\Traits\Attribute\OrderAttribute;
use App\Models\Order\Traits\Relationship\OrderRelationship;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
	use OrderAttribute, OrderRelationship;

    protected $fillable = ['id', 'transaction_no', 'cash', 'change', 'payable', 'discount', 'type', 'table_no', 'status', 'user_id'];
}
