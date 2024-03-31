<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cms;
use App\Models\Translations\CmsTranslation;
use DB;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement("SET foreign_key_checks=0");
        Cms::truncate();
        DB::statement("SET foreign_key_checks=1");

        Cms::updateOrCreate(
        [
            'id'            => 1,
            'slug'          => 'terms_conditions' ,
            'display_order' => '1' , 
            'status'        => '1',
            'content'       => 'Terms conditions Content',
            'page_name'     =>  'Terms conditions',

        ],
        );
        
        Cms::updateOrCreate(
        [   'id'            => 2,
            'slug'          => 'privacy_policy',
            'display_order' => '1' , 
            'status'        => '1',
            'content'       => 'Privacy Policy Content',
            'page_name'     =>  'Privacy Policy',

        ],
        );

        Cms::updateOrCreate(
        [
            'id'            => 3,
            'slug'          => 'about_us' ,
            'display_order' => '1' , 
            'status'        => '1',
            'content'       => 'About Us Content',
            'page_name'     =>  'About Us',

        ],
        );

        Cms::updateOrCreate(
        [
            'id'            => 4,
            'slug'          => 'refer_and_earn' ,
            'display_order' => '1' , 
            'status'        => '1',
            'content'       => 'This is content for Refer and Earn, Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet.',
            'page_name'     =>  'Refer and Earn',

        ],
        );
    }

  }