<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
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
                'name'        => 'Adobe Illustrato',
                'description' => 'Adobe Illustrato',
            ],
            [
                'name'        => 'Adobe Photoshop',
                'description' => 'Adobe Photoshop',
            ],
            [
                'name'        => 'Cold Calling',
                'description' => 'Cold Calling',
            ],
            [
                'name'        => 'COMMUNICATION',
                'description' => 'COMMUNICATION',
            ],
            [
                'name'        => 'Communication Skills',
                'description' => 'Communication Skills',
            ],
            [
                'name'        => 'CSS',
                'description' => 'CSS',
            ],
            [
                'name'        => 'English Fluency',
                'description' => 'English Fluency',
            ],
            [
                'name'        => 'HTML',
                'description' => 'HTML',
            ],
            [
                'name'        => 'Java',
                'description' => 'Java',
            ],
            [
                'name'        => 'JavaScript',
                'description' => 'JavaScript',
            ],

        ];

        foreach ($items as $item) {
            Skill::create($item);
        }

    }
}
