<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DEVELPER ROLE
        $role1 = Role::firstOrCreate([
		            'name' => 'developer',
		            // 'title' => 'Developer Team',
		            'guard_name' => 'web'
		        ]);
       	$permissions1 = Permission::whereIn('name',[
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-delete'
        ])->get();
       	$role1->syncPermissions($permissions1);

        // SUPER ADMIN ROLE
        $role2 = Role::firstOrCreate([
                    'name' => 'admin',
                    // 'title' => 'Admin',
                    'guard_name' => 'web'
                ]);
        $permissions2 = Permission::whereIn('name',[

            'customer-list',
            'customer-create',
            'customer-edit',
            'customer-delete',

            'country-list',
            // 'country-create',
            'country-edit',
            // 'country-delete',

            'states-list',
            'states-create',
            'states-edit',
            'states-delete',

            'city-list',
            'city-create',
            'city-edit',
            'city-delete',

            'post_code-list',
            'post_code-create',
            'post_code-edit',
            'post_code-delete',

            'notification-list',
            'master_panel-list',

            'setting-list',
            'setting-create',
            'setting-edit',
            'setting-delete',

            'cms-list',
            'cms-edit',

            'suggestion-list',

            'ticket-list',
            'ticket-create',
            'ticket-edit',
            'ticket-delete',

            'contact-reason-list',
            'contact-reason-create',
            'contact-reason-edit',
            'contact-reason-delete',

            'reason-list',
            'reason-create',
            'reason-edit',
            'reason-delete',

            'business-category-list',
            'business-category-create',
            'business-category-edit',
            'business-category-delete',

            'nested-category-list',
            'nested-category-create',
            'nested-category-edit',
            'nested-category-delete',

            'sub-admin-list',
            'sub-admin-create',
            'sub-admin-edit',
            'sub-admin-delete',

            'role-list',
            'role-edit',
            'role-create',
            'role-delete',

            'ticket-list',
            'ticket-create',
            'ticket-edit',
            'ticket-delete',

            'product-list',
            'product-create',
            'product-edit',
            'product-delete',

            'stock-list',
            'stock-create',
            'stock-edit',
            'stock-delete',

            'stock-transfer-list',
            'stock-transfer-create',
            'stock-transfer-edit',
            'stock-transfer-delete',

            'stock-report-list',
            'stock-report-create',
            'stock-report-edit',
            'stock-report-delete',

            'wish-list-list',
            'wish-list-create',
            'wish-list-edit',
            'wish-list-delete',

            'wish-return-list',
            'wish-return-create',
            'wish-return-edit',
            'wish-return-delete',

            'ssgc-suggestion-list',
            'ssgc-suggestion-create',
            'ssgc-suggestion-edit',
            'ssgc-suggestion-delete',

            'wish-suggestion-list',
            'wish-suggestion-create',
            'wish-suggestion-edit',
            'wish-suggestion-delete',

            'product-barcode-list',
            'product-barcode-create',
            'product-barcode-edit',
            'product-barcode-delete',

            'order-list',
            'order-create',
            'order-edit',
            'order-delete',
            
            'order-return-list',
            'order-return-create',
            'order-return-edit',
            'order-return-delete',


            'coupon-list',
            'coupon-create',
            'coupon-edit',
            'coupon-delete',

        ])->get();
        $role2->syncPermissions($permissions2);



        // Customer
        $role4 = Role::firstOrCreate([
          'name' => 'customer',
          // 'title' => 'Customer',
          'guard_name' => 'web',
        ]);

    }
}
