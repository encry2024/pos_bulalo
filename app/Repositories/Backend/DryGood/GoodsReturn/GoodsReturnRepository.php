<?php

namespace App\Repositories\Backend\DryGOod\GoodsReturn;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\DryGood\GoodsReturn\GoodsReturn;

class GoodsReturnRepository extends BaseRepository
{
	const MODEL = GoodsReturn::class;

	public function getForDataTable(){
		return $this->query()
				->with(['inventory' => function($q) {
					$q->withTrashed();
				}]);
	}
}