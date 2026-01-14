<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // denormalized for convenience
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();

            $table->string('title');
            $table->string('category')->nullable();       // e.g. venue, games, food
            $table->text('details')->nullable();
            $table->unsignedInteger('budget_estimate')->nullable(); // dollars
            $table->boolean('can_volunteer')->default(false);
            $table->boolean('anonymous')->default(false);

            $table->enum('status', ['pending','approved','rejected'])->default('pending');

            $table->timestamps();
            $table->index(['status','created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};