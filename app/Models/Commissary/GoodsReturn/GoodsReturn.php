<?php

namespace App\Models\Commissary\GoodsReturn;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commissary\GoodsReturn\Traits\Attribute\GoodsReturnAttribute;
use App\Models\Commissary\GoodsReturn\Traits\Relationship\GoodsReturnRelationship;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsReturn extends Model
{
	use GoodsReturnAttribute, GoodsReturnRelationship, SoftDeletes;

	protected $table = 'goods_returns';

    protected $fillable = ['id','inventory_id','date','quantity','cost','total_cost','reason','witness'];

    protected $dates = ['deleted_at'];
}
