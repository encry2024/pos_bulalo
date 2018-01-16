<?php

namespace App\Repositories\Backend\DryGood\Dispose;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\DryGood\Dispose\Dispose;

class DisposeRepository extends BaseRepository
{
	const MODEL = Dispose::class;

	public function getForDataTable(){
		return $this->query()
				->with(['inventory' => function($q) {
					$q->withTrashed();
				}]);
	}
}