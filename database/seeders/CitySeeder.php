<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $city_sql = 'database/sql/cities.sql';
        DB::unprepared(file_get_contents($city_sql));
        $this->command->info('State Table Seeder Run Successfully!');
    }
}
