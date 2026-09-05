<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percent', 'fixed'])->default('percent');
            $table->decimal('value', 10, 2);                       // 10 => 10% or ৳10
            $table->decimal('min_order_amount', 10, 2)->nullable(); // minimum cart subtotal
            $table->decimal('max_discount', 10, 2)->nullable();     // cap for percent coupons
            $table->unsignedInteger('usage_limit')->nullable();     // null = unlimited
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('free_shipping')->default(false);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_area')->nullable()->after('city');
            $table->string('coupon_code')->nullable()->after('subtotal');
            $table->decimal('discount', 10, 2)->default(0)->after('coupon_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_area', 'coupon_code', 'discount']);
        });

        Schema::dropIfExists('coupons');
    }
};
