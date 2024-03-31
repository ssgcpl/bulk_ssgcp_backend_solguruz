<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Country;
use DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        Country::truncate();
        DB::statement("SET foreign_key_checks=1");
        
        Country::updateOrCreate(
            ['country_code' => '+91',
             'name' => 'India',
             'flag' => 'flags/india.png',
             'status' => '1',
            ]
        );
    }

}
