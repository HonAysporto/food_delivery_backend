<?php

namespace App\Services;

use App\Models\AdminWallet;
use App\Models\Order;
use App\Models\PlatformSetting;
use App\Models\RestaurantWallet;
use App\Models\Rider;
use App\Models\RiderWallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public static function creditOrder(Order $order)
    {
        DB::transaction(function () use ($order) {

            // Prevent double payment
            if (
                Transaction::where('order_id', $order->id)
                    ->where('type', 'restaurant_credit')
                    ->exists()
            ) {
                return;
            }

            $settings = PlatformSetting::first();

            $restaurantAmount =
                $order->total *
                ($settings->restaurant_percentage / 100);

            $riderAmount =
                $order->total *
                ($settings->rider_percentage / 100);

            $adminAmount =
                $order->total *
                ($settings->admin_percentage / 100);

            // Restaurant Wallet
            $restaurantWallet = RestaurantWallet::firstOrCreate(
                [
                    'restaurant_id' => $order->restaurant_id
                ],
                [
                    'balance' => 0
                ]
            );

       $restaurantWallet->increment('balance', $restaurantAmount);

$restaurantWallet->increment(
    'total_earned',
    $restaurantAmount
);

            // Rider Wallet
            $rider = Rider::where(
                'user_id',
                $order->rider_id
            )->first();

            if ($rider) {

                $riderWallet = RiderWallet::firstOrCreate(
                    [
                        'rider_id' => $rider->id
                    ],
                    [
                        'balance' => 0
                    ]
                );

            $riderWallet->increment('balance', $riderAmount);

$riderWallet->increment(
    'total_earned',
    $riderAmount
);
            }

            // Admin Wallet
            $adminWallet = AdminWallet::first();

         $adminWallet->increment('balance', $adminAmount);

$adminWallet->increment(
    'total_earned',
    $adminAmount
);

            // Restaurant Transaction
            Transaction::create([
                'order_id' => $order->id,
                'restaurant_id' => $order->restaurant_id,
                'type' => 'restaurant_credit',
                'amount' => $restaurantAmount,
                'description' => "Restaurant earnings from Order #{$order->id}"
            ]);

            // Rider Transaction
            if ($rider) {

                Transaction::create([
                    'order_id' => $order->id,
                    'rider_id' => $rider->id,
                    'type' => 'rider_credit',
                    'amount' => $riderAmount,
                    'description' => "Delivery earnings from Order #{$order->id}"
                ]);

            }

            // Admin Transaction
            Transaction::create([
                'order_id' => $order->id,
                'type' => 'admin_commission',
                'amount' => $adminAmount,
                'description' => "Platform commission from Order #{$order->id}"
            ]);

        });
    }
}