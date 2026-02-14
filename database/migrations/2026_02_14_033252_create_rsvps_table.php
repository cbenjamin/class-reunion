<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // yes | no | maybe
            $table->string('status', 10);

            $table->unsignedTinyInteger('guest_count')->default(0);
            $table->text('note')->nullable();

            $table->timestamps();

            // Only 1 RSVP per user
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rsvps');
    }
};