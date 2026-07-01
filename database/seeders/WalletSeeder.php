<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminWallet;
use App\Models\PlatformSetting;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        AdminWallet::firstOrCreate(
            ['id' => 1],
            [
                'balance' => 0
            ]
        );

        PlatformSetting::firstOrCreate(
            ['id' => 1],
            [
                'restaurant_percentage' => 90,
                'rider_percentage' => 5,
                'admin_percentage' => 5
            ]
        );
    }
}