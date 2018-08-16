<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name','administrator')->first();
        $role_member = Role::where('name', 'member')->first();

        $user = new User();
        $user->name = 'Dickson Foo';
        $user->email = 'ad@ad.com';
        $user->password = bcrypt(123123);
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = 'Jane Ko';
        $user->email = 'da@da.com';
        $user->password = bcrypt(123123);
        $user->save();
        $user->roles()->attach($role_member);
    }
}
