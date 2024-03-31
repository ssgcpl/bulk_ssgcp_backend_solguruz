<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            PermissionTableSeeder::class,
            RolesTableSeeder::class,
            SettingsTableSeeder::class,
            LayoutTypeSeeder::class,
            UsersTableSeeder::class,
            CountrySeeder::class,
            ReasonSeeder::class,
            ItemTypeSeeder::class,
            CmsSeeder::class
        ]);
    }
}
