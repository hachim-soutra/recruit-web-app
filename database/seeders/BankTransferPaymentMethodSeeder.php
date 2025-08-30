<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;
use App\Enum\Payments\PaymentMethodStatusEnum;

class BankTransferPaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::updateOrCreate([
            'slug' => 'bank_transfer',
        ], [
            'title' => 'Bank Transfer',
            'description' => 'Payment via bank transfer',
            'status' => PaymentMethodStatusEnum::ACTIVE,
        ]);
    }
}
