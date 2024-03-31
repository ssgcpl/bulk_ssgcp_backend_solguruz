<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LayoutType;
use DB;

class LayoutTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        	LayoutType::updateOrCreate(
            [
             'name' => 'books',
             'status' => 'active',
            ]);

        	/*LayoutType::updateOrCreate(
            [
             'name' => 'my_return',
             'status' => 'active',
            ]);*/
            LayoutType::updateOrCreate(
            [
             'name' => 'digital_coupons',
             'status' => 'active',
            ]);
           /* LayoutType::updateOrCreate(
            [
             'name' => 'url',
             'status' => 'active',
            ]);*/
       
    }
}
