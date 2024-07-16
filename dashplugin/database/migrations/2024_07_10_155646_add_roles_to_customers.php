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
        Schema::table('dash_customers', function (Blueprint $table) {
            $table->boolean('super_user')->default(0);
            $table->boolean('manage_customers')->default(0);
            $table->text('permissions')->nullable();
            $table->timestamp('last_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dash_customers', function (Blueprint $table) {
            $table->dropColumn('super_user');
            $table->dropColumn('manage_customers');
            $table->dropColumn('permissions');
            $table->dropColumn('last_login');
        });
    }
};
