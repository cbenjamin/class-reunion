<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->text('memory');             // favorite memory (main quote)
            $table->string('teacher')->nullable(); // teacher shoutout
            $table->string('song')->nullable();    // song that defined HS
            $table->text('now')->nullable();       // “where I am now”
            $table->boolean('anonymous')->default(false);

            $table->string('status')->default('pending'); // pending|approved|rejected
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
            $table->index(['status','is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};