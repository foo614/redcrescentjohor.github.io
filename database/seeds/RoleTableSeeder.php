<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'administrator';
        $role->description = 'A admin user';
        $role->save();

        $role = new Role();
        $role->name = 'member';
        $role->description = 'A member user';
        $role->save();
    }
}
