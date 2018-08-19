<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class HospitalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hospitals')->insert([
            'name' => 'KPJ Johor Specialist Hospital',
            'address' => '39B, Jalan Abdul Samad, Kolam Ayer, 80100 Johor Bahru, Johor, Malaysia',
            'email' => 'KPJ@specialist.com',
            'contact' => '07-225 3000',
            'map_lat' => '1.475979',
            'map_lng' => '103.74124200000006',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
