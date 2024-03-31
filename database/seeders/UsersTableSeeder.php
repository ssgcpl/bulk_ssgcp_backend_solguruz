<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	
        for ($i=0; $i <= 1; $i++) {
                if($i == 0){
                    $role = Role::where('name','developer')->first();
                    $phone = '1234567890';
                }else if($i == 1){
                    $role = Role::where('name','admin')->first();
                    $phone = '4569078123';
                }else if($i == 2){
                    $role = Role::where('name','vendor')->first();
                    $phone = '0987654321';
                }
                /*$user = User::firstOrCreate([
                            'first_name' => $role->name,
                            'email' => strtolower($role->name).'@mail.com',
                            'mobile_number' => $phone,
                            'password' => Hash::make('12345678'),
                            'user_type' => $role->name,
                            'verified' => 1,
                            'registered_on' => 'web',
                            'status' => 'active',
                            'email_verified_at' => date('Y-m-d'),
                        ]);
                */
                 $userdata = User::where('user_type','admin')->count(); 
                 if($userdata=='0'){
                $user = User::updateOrCreate([
                            'first_name' => $role->name,
                            'email' => strtolower($role->name).'@mail.com',
                            'mobile_number' => $phone,
                            'password' => Hash::make('12345678'),
                            'user_type' => $role->name,
                            'verified' => '1',
                            'registered_on' => 'web',
                            'status' => 'active',
                            'email_verified_at' => date('Y-m-d'),
                        ]);

                   $user->assignRole([$role->id]);
              }
                if($role->name != 'developer'){
                    $this->command->getOutput()->writeln("<question>".strtoupper($role->name)." Panel Credentials</question>");
                    $this->command->getOutput()->writeln("<comment>Username:</comment><info>".strtolower($role->name).'@mail.com'."</info>");
                    $this->command->getOutput()->writeln("<comment>Password:</comment><info>12345678</info>");
                }

        }
        //factory('App\Models\User', 10)->create();
    }

 
}
