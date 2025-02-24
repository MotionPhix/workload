<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('invitations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('brand_id')->constrained()->onDelete('cascade');
      $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
      $table->string('email');
      $table->string('role')->default('employee'); // e.g., admin, employee
      $table->string('token')->unique(); // For invitation link
      $table->timestamp('accepted_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('invitations');
  }
};
