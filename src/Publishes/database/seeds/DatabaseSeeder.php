<?php

use Illuminate\Database\Seeder;

/**
 * composer dump-autoload
 * 執行全部的 seeder
 * php artisan db:seed
 * 
 * 只執行指定的 seeder
 * php artisan db:seed --class=UsersTableSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoleUsersTableSeeder::class);
        // $this->call(SettingsTableSeeder::class);
    }
}
