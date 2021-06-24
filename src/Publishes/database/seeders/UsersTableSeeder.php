<?php

namespace Database\Seeders;

// use Hash;
use Illuminate\Database\Seeder;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email' => 'qun@onepoint.com.tw',
            'username' => 'onepoint',
            'realname' => 'Admin',
            'password' => bcrypt('123456')
        ]);
    }
}
