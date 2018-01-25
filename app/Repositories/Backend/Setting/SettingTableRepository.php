<?php

namespace App\Repositories\Backend\Setting;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting\Table;

class SettingTableRepository extends BaseRepository
{
	const MODEL = Table::class;

	public function getForDataTable(){
		return $this->query()
				->select('id', 'number', 'price');
	}
}