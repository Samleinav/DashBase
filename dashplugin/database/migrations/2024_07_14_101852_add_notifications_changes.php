<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dash_notifications', function (Blueprint $table) {
            $table->boolean('is_global')->default(0);
        });

        Schema::create('dash_global_notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('notification_id');
            $table->integer('customer_id');
            $table->boolean('is_read')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dash_notifications', function (Blueprint $table) {
            $table->dropColumn('is_global');
        });

        Schema::dropIfExists('dash_global_notifications');
            
    }
};
