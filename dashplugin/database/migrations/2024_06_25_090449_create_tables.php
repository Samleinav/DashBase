<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('dash_customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->dateTime('confirmed_at')->nullable();
            $table->string('avatar')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('country', 120)->nullable();
            $table->string('state', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('address')->nullable();
            $table->string('status', 60)->default('pending');
            $table->timestamps();
        });

        Schema::create('dash_customer_password_resets', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('dash_services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->decimal('price', 15, 0)->nullable()->unsigned();
            $table->foreignId('currency_id')->nullable();
            $table->string('image')->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('dash_services_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->foreignId('dash_services_id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->primary(['lang_code', 'dash_services_id'], 'dash_services_translations_primary');
        });

        Schema::create('dash_taxes', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->float('percentage', 8, 6)->nullable();
            $table->integer('priority')->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('dash_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->index();
            $table->foreignId('payment_id')->nullable()->index();
            $table->string('description', 400)->nullable();
            $table->morphs('reference');
            $table->string('code')->unique();
            $table->unsignedDecimal('sub_total', 15);
            $table->unsignedDecimal('tax_amount', 15)->default(0);
            $table->unsignedDecimal('discount_amount', 15)->default(0);
            $table->unsignedDecimal('amount', 15);
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('customer_address');
            $table->string('status')->index()->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('dash_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id');
            $table->string('name');
            $table->string('description', 400)->nullable();
            $table->unsignedInteger('qty');
            $table->unsignedDecimal('sub_total', 15);
            $table->unsignedDecimal('tax_amount', 15)->default(0);
            $table->unsignedDecimal('discount_amount', 15)->default(0);
            $table->unsignedDecimal('amount', 15);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('dash_customer_password_resets');
        Schema::dropIfExists('dash_customers');
        Schema::dropIfExists('dash_services');
        Schema::dropIfExists('dash_taxes');
        Schema::dropIfExists('dash_invoices');
        Schema::dropIfExists('dash_invoice_items');
    }
};
