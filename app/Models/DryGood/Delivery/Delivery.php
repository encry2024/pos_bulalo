<?php

namespace App\Models\DryGood\Delivery;

use Illuminate\Database\Eloquent\Model;
use App\Models\DryGood\Delivery\Traits\Attribute\DeliveryAttribute;
use App\Models\DryGood\Delivery\Traits\Relationship\DeliveryRelationship;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
	use DeliveryAttribute, DeliveryRelationship, SoftDeletes;

	protected $table = 'drygood_deliveries';

    protected $fillable = ['id', 'item_id', 'branch_id', 'quantity', 'date', 'price', 'deliver_to'];

    protected $dates = ['deleted_at'];
}
