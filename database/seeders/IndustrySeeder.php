<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
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
                'name'        => 'Accounting/Taxation',
                'description' => 'Accounting/Taxation',
            ],
            [
                'name'        => 'Advertising/PR',
                'description' => 'Advertising/PR',
            ],
            [
                'name'        => 'Agriculture/Fertilizer/Pesticide',
                'description' => 'Agriculture/Fertilizer/Pesticide',
            ],
            [
                'name'        => 'Apparel/Clothing',
                'description' => 'Apparel/Clothing',
            ],
            [
                'name'        => 'Architecture/Interior Design',
                'description' => 'Architecture/Interior Design',
            ],
            [
                'name'        => 'Arts/ Entertainment',
                'description' => 'Arts/ Entertainment',
            ],
            [
                'name'        => 'AutoMobile',
                'description' => 'AutoMobile',
            ],
            [
                'name'        => 'Aviation',
                'description' => 'Aviation',
            ],
            [
                'name'        => 'Banking/Financial Services',
                'description' => 'Banking/Financial Services',
            ],
            [
                'name'        => 'BPO',
                'description' => 'BPO',
            ],

        ];

        foreach ($items as $item) {
            Industry::create($item);
        }

    }
}
