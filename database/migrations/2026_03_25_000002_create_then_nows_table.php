<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('then_nows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('then_disk')->default('public');
            $table->string('then_path');
            $table->string('now_disk')->default('public');
            $table->string('now_path');
            $table->string('caption')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique('user_id'); // one pair per classmate
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('then_nows');
    }
};
