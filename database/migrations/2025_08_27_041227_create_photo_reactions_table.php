<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photo_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20); // like|love|laugh|wow|sad|party
            $table->timestamps();

            $table->unique(['photo_id', 'user_id']); // one reaction per user per photo
            $table->index(['photo_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_reactions');
    }
};