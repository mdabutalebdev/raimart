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
        Schema::table('products', function (Blueprint $table) {
            $table->string('main_image')->nullable()->after('slug');
            $table->text('short_description')->nullable()->after('name');
            $table->json('attributes')->nullable()->after('is_active');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->json('options')->nullable()->after('product_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_and_order_items_tables', function (Blueprint $table) {
            //
        });
    }
};
