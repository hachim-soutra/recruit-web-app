<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $state_sql = 'database/sql/states.sql';
        DB::unprepared(file_get_contents($state_sql));
        $this->command->info('State Table Seeder Run Successfully!');
    }
}
