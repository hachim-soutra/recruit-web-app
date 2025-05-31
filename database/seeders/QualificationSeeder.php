<?php

namespace Database\Seeders;

use App\Models\Qualification;
use Illuminate\Database\Seeder;

class QualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name'        => 'Matric in Arts',
                'description' => 'Matric in Arts',
            ],
            [
                'name'        => 'Masters in Arts',
                'description' => 'Masters in Arts',
            ],
            [
                'name'        => 'Bachelors in Arts',
                'description' => 'Bachelors in Arts',
            ],
            [
                'name'        => 'A-Levels',
                'description' => 'A-Levels',
            ],
            [
                'name'        => 'Faculty of Arts',
                'description' => 'Faculty of Arts',
            ],
            [
                'name'        => 'Masters in Business Administration',
                'description' => 'Masters in Business Administration',
            ],
            [
                'name'        => 'Matric in Science',
                'description' => 'Matric in Science',
            ],
            [
                'name'        => 'Bachelors in Architecture',
                'description' => 'Bachelors in Architecture',
            ],
            [
                'name'        => 'O-Levels',
                'description' => 'O-Levels',
            ],
            [
                'name'        => 'Faculty of Science (Pre-medical)',
                'description' => 'Faculty of Science (Pre-medical)',
            ],
            [
                'name'        => 'BCA',
                'description' => 'Bachelors of Computer Application',
            ],
            [
                'name'        => 'MCA',
                'description' => 'Masters of Computer Application',
            ],

        ];

        foreach ($items as $item) {
            Qualification::create($item);
        }
    }
}
