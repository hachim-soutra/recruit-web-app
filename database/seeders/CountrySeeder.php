<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country_sql = 'database/sql/countries.sql';
        DB::unprepared(file_get_contents($country_sql));
        $this->command->info('Country Table Seeder Run Successfully!');
    }
}
