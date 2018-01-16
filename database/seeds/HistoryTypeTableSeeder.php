<?php

use Database\TruncateTable;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;
use Illuminate\Support\Facades\DB;

/**
 * Class HistoryTypeTableSeeder.
 */
class HistoryTypeTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple([
            'history_types', 'history', 'categories', 'settings', 'tables'
        ]);

        $types = [
            [
                'name'       => 'User',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Role',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('history_types')->insert($types);

        $this->enableForeignKeys();


        $categories = [
            [
                'id' => 1,
                'name' => 'Food',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'name' => 'Food Supply',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'name' => 'Food Material',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 4,
                'name' => 'Cleaning Material',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        DB::table('categories')->insert($categories);

        $settings = [
            [
                'id'            => 1,
                'name'          => 'Senior Citizen',
                'discount'      => 1,
                'description'   => 'none'
            ],
            [
                'id'            => 2,
                'name'          => 'PWD',
                'discount'      => 1,
                'description'   => 'none'
            ],
        ];

        DB::table('settings')->insert($settings);

        DB::table('tables')->insert([['count' => 5]]);
    }
}
