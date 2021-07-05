<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RoleUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_users')->insert([
            'user_id' => 1,
            'role_id' => 1
        ]);
    }
}