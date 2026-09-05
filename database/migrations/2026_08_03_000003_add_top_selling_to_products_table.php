<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_top_selling')->default(false)->after('is_new_arrival');
            $table->unsignedInteger('top_selling_order')->default(0)->after('is_top_selling');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_top_selling', 'top_selling_order']);
        });
    }
};
