<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {

            $table->id();

            $table->decimal('restaurant_percentage', 5, 2)
                ->default(90);

            $table->decimal('rider_percentage', 5, 2)
                ->default(5);

            $table->decimal('admin_percentage', 5, 2)
                ->default(5);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};