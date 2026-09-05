<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();  // rich HTML from the Quill editor
            $table->string('meta_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('show_in_footer')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed the policy pages the footer links to, so they exist to edit right away.
        $now = now();
        DB::table('pages')->insert([
            ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'content' => '<p>Write your privacy policy here.</p>', 'is_active' => true, 'show_in_footer' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Terms & Conditions', 'slug' => 'terms-conditions', 'content' => '<p>Write your terms and conditions here.</p>', 'is_active' => true, 'show_in_footer' => true, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Return & Refund Policy', 'slug' => 'return-refund-policy', 'content' => '<p>Write your return and refund policy here.</p>', 'is_active' => true, 'show_in_footer' => true, 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
