<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
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
                'name' => 'Irish',
                'body' => 'Irish',
            ],
            [
                'name' => 'English',
                'body' => 'English',
            ],
            [
                'name' => 'Spanish',
                'body' => 'Spanish',
            ],
            [
                'name' => 'Mandarin Chinese',
                'body' => 'Mandarin Chinese',
            ],
            [
                'name' => 'Hindi',
                'body' => 'Hindi',
            ],
            [
                'name' => 'French',
                'body' => 'French',
            ],
            [
                'name' => 'Standard Arabic',
                'body' => 'Standard Arabic',
            ],
            [
                'name' => 'Bengali',
                'body' => 'Bengali',
            ],
            [
                'name' => 'Russian',
                'body' => 'Russian',
            ],
            [
                'name' => 'Portuguese',
                'body' => 'Portuguese',
            ],
            [
                'name' => 'Indonesian',
                'body' => 'Indonesian',
            ],

        ];

        foreach ($items as $item) {
            Language::create($item);
        }
    }
}
