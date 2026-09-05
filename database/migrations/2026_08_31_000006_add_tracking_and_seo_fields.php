<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier_name')->nullable()->after('status');
            $table->string('tracking_number')->nullable()->after('courier_name');
            $table->text('admin_note')->nullable()->after('tracking_number');   // internal, never shown to the customer
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('description');
            $table->string('meta_description', 500)->nullable()->after('meta_title');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['courier_name', 'tracking_number', 'admin_note']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};
