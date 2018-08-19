<?php

use Illuminate\Database\Seeder;
use App\Branch;

class BranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch = new Branch();
        $branch->name = 'MRC-JOHOR';
        $branch->address = 'NO. 3 JALAN BACANG, TAMPOI 81200';
        $branch->contact = '07-2371544';
        $branch->email = 'ibupejabat@redcrescentjohor.gov.my';
        $branch->fax = '2384892';
        $branch->map_lat = '1.493780';
        $branch->map_lng = '103.702731';
        $branch->save();
    }
}
