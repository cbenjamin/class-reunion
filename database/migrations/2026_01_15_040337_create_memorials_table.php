<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('memorials', function (Blueprint $table) {
            $table->id();
            $table->string('classmate_name');
            $table->string('graduation_year')->nullable();
            $table->string('relationship')->nullable();      // submitter’s relation to classmate
            $table->string('submitter_name')->nullable();
            $table->string('submitter_email')->nullable();
            $table->string('obituary_url')->nullable();

            $table->text('bio')->nullable();                 // message / tribute
            $table->string('disk')->default('public');
            $table->string('photo_path')->nullable();

            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->boolean('is_featured')->default(false);

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('memorials');
    }
};