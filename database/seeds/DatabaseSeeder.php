<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(BloodTypeTableSeeder::class);
        $this->call(BranchTableSeeder::class);
        $this->call(MembershipTypeTableSeeder::class);
        $this->call(HospitalTableSeeder::class);
        $this->call(PostCategoryTableSeeder::class);
    }
}
