<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_wallets', function (Blueprint $table) {

            $table->decimal('total_earned',12,2)->default(0)->after('balance');

            $table->decimal('total_withdrawn',12,2)->default(0)->after('total_earned');

        });

        Schema::table('rider_wallets', function (Blueprint $table) {

            $table->decimal('total_earned',12,2)->default(0)->after('balance');

            $table->decimal('total_withdrawn',12,2)->default(0)->after('total_earned');

        });

        Schema::table('admin_wallets', function (Blueprint $table) {

            $table->decimal('total_earned',12,2)->default(0)->after('balance');

        });
    }

    public function down(): void
    {
        Schema::table('restaurant_wallets', function (Blueprint $table) {

            $table->dropColumn([
                'total_earned',
                'total_withdrawn'
            ]);

        });

        Schema::table('rider_wallets', function (Blueprint $table) {

            $table->dropColumn([
                'total_earned',
                'total_withdrawn'
            ]);

        });

        Schema::table('admin_wallets', function (Blueprint $table) {

            $table->dropColumn('total_earned');

        });
    }
};