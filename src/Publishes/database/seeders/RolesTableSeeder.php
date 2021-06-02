<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role_name' => 'Admin',
            'status' => '啟用',
            'sort' => 1,
            'permissions' => '{"read-UserController":true,"update-UserController":true,"create-UserController":true,"delete-UserController":true,"read-RoleController":true,"update-RoleController":true,"create-RoleController":true,"delete-RoleController":true,"read-SettingController":true,"update-SettingController":true}'
        ]);
    }
}
