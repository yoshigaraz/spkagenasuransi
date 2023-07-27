<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employes')->insert([
            'name' => 'admin',
            'position_id' => 1,
            'address' => 'Jalan Proklamasi Kemerdekaan Indonesia',
            'gender' => 'Laki',
            'date_in' => Carbon::now(),
        ]);
    }
}
