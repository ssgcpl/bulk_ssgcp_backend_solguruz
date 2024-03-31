<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reason;
use DB;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::statement("SET foreign_key_checks=0");
        Reason::truncate();
        DB::statement("SET foreign_key_checks=1");
        
       	Reason::updateOrCreate(
            ['name' => 'Need help for How to place order',
             'type' => 'customer_ticket',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
        Reason::updateOrCreate(
            ['name' => 'Need help for How to do payment',
             'type' => 'customer_ticket',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
        /*Reason::updateOrCreate(
            ['name' => 'Available in lower price',
             'type' => 'customer_order_cancel',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
        Reason::updateOrCreate(
            ['name' => 'Guest arrvied, so order cancelled',
             'type' => 'customer_order_cancel',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
         Reason::updateOrCreate(
            ['name' => 'Want to purchase later',
             'type' => 'customer_order_cancel',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
        Reason::updateOrCreate(
            ['name' => 'Guest arrvied, so order cancelled',
             'type' => 'customer_order_cancel',
             'status' => 'active',
            ]
        );
        Reason::updateOrCreate(
            ['name' => 'Already purchased from other site',
             'type' => 'customer_order_cancel',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
        Reason::updateOrCreate(
            ['name' => 'Not required for now',
             'type' => 'customer_order_cancel',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );*/
        Reason::updateOrCreate(
            ['name' => 'Need help',
             'type' => 'customer_ticket',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
        Reason::updateOrCreate(
            ['name' => 'Need admin help',
             'type' => 'customer_ticket',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
        /* Reason::updateOrCreate(
            ['name' => 'Wrong book arrived',
             'type' => 'customer_order_cancel',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
        Reason::updateOrCreate(
            ['name' => 'Other',
             'type' => 'customer_order_cancel',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );*/
        Reason::updateOrCreate(
            ['name' => 'Not required this item',
             'type' => 'customer_ticket',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
        /*  Reason::updateOrCreate(
            ['name' => 'Delivery time is too much',
             'type' => 'customer_order_cancel',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
        Reason::updateOrCreate(
            ['name' => 'Video quality is not good',
             'type' => 'customer_order_cancel',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
        Reason::updateOrCreate(
            ['name' => 'Name',
             'type' => 'customer_order_cancel',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );*/
        Reason::updateOrCreate(
            ['name' => 'Item not required',
             'type' => 'customer_ticket',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );
       /* Reason::updateOrCreate(
            ['name' => 'Wrong item delivered',
             'type' => 'customer_order_cancel',
             'status' => 'active',
             'added_by'=>'2',
            ]
        );*/

        

    }
}
