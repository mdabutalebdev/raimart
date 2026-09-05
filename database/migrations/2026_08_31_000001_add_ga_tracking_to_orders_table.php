<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // GA4 client_id (from the browser _ga cookie) so the server-side
            // Measurement Protocol purchase event attributes to the right user/session.
            $table->string('ga_client_id')->nullable()->after('user_id');
            $table->string('ga_session_id')->nullable()->after('ga_client_id');
            // Stamped when the purchase event has been sent to GA4, so callback +
            // webhook (or a page refresh) can never double-count the same order.
            $table->timestamp('purchase_tracked_at')->nullable()->after('ga_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['ga_client_id', 'ga_session_id', 'purchase_tracked_at']);
        });
    }
};
