<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_settings', function (Blueprint $table) {
            $table->boolean('rsvp_enabled')->default(false)->after('details'); // adjust "after" if needed
        });
    }

    public function down(): void
    {
        Schema::table('event_settings', function (Blueprint $table) {
            $table->dropColumn('rsvp_enabled');
        });
    }
};