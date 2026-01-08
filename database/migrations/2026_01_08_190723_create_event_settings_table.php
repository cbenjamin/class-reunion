<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_settings', function (Blueprint $table) {
            $table->id();
            $table->string('event_name')->nullable();      // optional, defaults to APP_NAME
            $table->date('event_date')->nullable();
            $table->string('event_time')->nullable();      // free-form like "6–10 PM"
            $table->string('venue')->nullable();
            $table->string('address')->nullable();
            $table->longText('details')->nullable();       // rich text (HTML)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_settings');
    }
};