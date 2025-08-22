<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invitation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('grad_year')->nullable();
            $table->json('answers')->nullable();
            $table->enum('status', ['pending','approved','denied'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->string('approval_token')->nullable()->unique();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('invitation_requests');
    }
};