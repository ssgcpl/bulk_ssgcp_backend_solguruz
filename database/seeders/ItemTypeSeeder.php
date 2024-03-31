<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemType;
use DB;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ItemType::updateOrCreate(
            [
                'name' => 'books',
                'status' => 'active',
            ]
        );

        ItemType::updateOrCreate(
            [
                'name' => 'e_books',
                'status' => 'active',
            ]
        );
        ItemType::updateOrCreate(
            [
                'name' => 'packages',
                'status' => 'active',
            ]
        );

        ItemType::updateOrCreate(
            [
                'name' => 'videos',
                'status' => 'active',
            ]
        );

        ItemType::updateOrCreate(
            [
                'name' => 'courses',
                'status' => 'active',
            ]
        );
        ItemType::updateOrCreate(
            [
                'name' => 'tests',
                'status' => 'active',
            ]
        );
    }
}
