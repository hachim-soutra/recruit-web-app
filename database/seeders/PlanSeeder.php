<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Enum\Payments\PlanForEnum;
use App\Enum\Payments\PlanStatusEnum;
use App\Enum\Payments\PlanTypeStatusEnum;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::where(
            ['slug' => 'Professional', 'plan_for' => PlanForEnum::EMPLOYER, 'plan_type' => PlanTypeStatusEnum::SITE, 'status' => PlanStatusEnum::ACTIVE],
        )->update(
            [
                'best_value' => true,
                'badge_text' => 'unlimited listings, 90 days exposure, 24/7 premium support, advanced reports, dedicated account manager',
                'features' => [
                    '90 Days Exposure',
                    '24/7 Premium Support',
                    'Advanced Reports',
                    'Dedicated Account Manager',
                ],
            ]
        );

        Plan::where(
            ['slug' => 'Standard', 'plan_for' => PlanForEnum::EMPLOYER, 'plan_type' => PlanTypeStatusEnum::SITE, 'status' => PlanStatusEnum::ACTIVE],
        )->update(
            [
                'best_value' => false,
                'badge_text' => 'unlimited listings, 30 days exposure, standard support, basic reports',
                'features' => [
                    '30 Days Exposure',
                    'Standard Support',
                    'Basic Reports',
                ],
            ]
        );

        Plan::where(
            ['plan_for' => PlanForEnum::EMPLOYER, 'plan_type' => PlanTypeStatusEnum::SITE, 'status' => PlanStatusEnum::ACTIVE],
        )
        ->whereNotIn('slug', ['Professional', 'Standard'])
        ->update(
            [
                'best_value' => false,
                'badge_text' => 'unlimited listings, 7 days exposure, standard support, basic reports',
                'features' => [
                    '7 Days Exposure',
                    'Standard Support',
                    'Basic Reports',
                ],
            ]
        );
    }
}
