<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('caption')->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('photos');
    }
};