<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('city')->nullable()->after('email');
            $table->string('state')->nullable()->after('city');
            $table->decimal('lat', 10, 7)->nullable()->after('state');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
            $table->boolean('share_location')->default(true)->after('lng');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['city','state','lat','lng','share_location']);
        });
    }
};