<?php

namespace App\Models\Order\Traits\Relationship;

use App\Models\Access\User\User;
use App\Models\OrderList\OrderList;
use App\Models\Setting\Table;

/**
 * Class RoleRelationship.
 */
trait OrderRelationship
{

	public function order_list(){
		return $this->hasMany(OrderList::class);
	}

	public function user(){
		return $this->belongsTo(User::class);
	}

	public function table(){
		return $this->belongsTo(Table::class, 'table_no');
	}
}