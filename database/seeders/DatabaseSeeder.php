<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
           UsersSeeder::class,
           ModulsSeeder::class,
           PrivilegesSeeder::class,
           PrivilegeRolesSeeder::class,
           SettingsSeeder::class,
           EmailTemplatesSeeder::class,
        ]);
    }
}
