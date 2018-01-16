<?php

use Database\TruncateTable;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;
use Illuminate\Support\Facades\DB;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
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
        $this->truncateMultiple([config('access.users_table'), 'social_logins']);

        //Add the master administrator, user id of 1
        $users = [
            [
                'first_name'        => 'Admin',
                'last_name'         => 'Istrator',
                'email'             => 'admin@admin.com',
                'password'          => bcrypt('1234'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'POS',
                'last_name'         => 'Admin',
                'email'             => 'pos_admin@pos.com',
                'password'          => bcrypt('1234'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'Commissary',
                'last_name'         => 'User',
                'email'             => 'commissary@commissary.com',
                'password'          => bcrypt('1234'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
             [
                'first_name'        => 'Dry Good',
                'last_name'         => 'User',
                'email'             => 'drygood@drygood.com',
                'password'          => bcrypt('1234'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'Default',
                'last_name'         => 'User',
                'email'             => 'pos@pos.com',
                'password'          => bcrypt('1234'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];


        DB::table(config('access.users_table'))->insert($users);

        $this->enableForeignKeys();
    }
}
