<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

             DB::table('positions')->insert([
            'name' => 'owner',
             ]);
             DB::table('positions')->insert([
            'name' => 'manager',
             ]);
             DB::table('positions')->insert([
            'name' => 'admin',
             ]);
             DB::table('positions')->insert([
            'name' => 'staff',
             ]);
    }
}
