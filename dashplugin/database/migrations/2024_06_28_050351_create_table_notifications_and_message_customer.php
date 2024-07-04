<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dash_notifications', function (Blueprint $table) {
            $table->id();
            
            $table->string('title');
            $table->text('content');
            $table->string('status')->default('unread');
            $table->string('link')->nullable();
            $table->string('image')->nullable();
            $table->string('type')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('service_id')->nullable();
            $table->timestamps();
        });

        Schema::create('dash_messages', function (Blueprint $table) {
            $table->id();
            
            $table->string('title');
            $table->text('content');
            $table->string('status')->default('unread');
            $table->string('link')->nullable();
            $table->string('image')->nullable();
            $table->string('type')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('service_id')->nullable();
            $table->timestamps();
        });

        Schema::create('dash_messages_replies', function (Blueprint $table) {
            $table->id();
            
            $table->string('title');
            $table->text('content');
            $table->string('status')->default('unread');
            $table->string('link')->nullable();
            $table->string('image')->nullable();
            $table->string('type')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('message_id')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dash_notifications');
        schema::dropIfExists('dash_messages');
        schema::dropIfExists('dash_messages_replies');
    }
};
