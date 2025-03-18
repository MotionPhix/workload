<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('project_tags', function (Blueprint $table) {
      $table->id();
      $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
      $table->string('name');
      $table->string('color')->default('#3b82f6'); // Default blue
      $table->timestamps();

      $table->unique(['brand_id', 'name']);
    });

    Schema::create('project_tag', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->cascadeOnDelete();
      $table->foreignId('project_tag_id')->constrained()->cascadeOnDelete();
      $table->timestamps();

      $table->unique(['project_id', 'project_tag_id']);
    });

    Schema::create('project_member', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->cascadeOnDelete();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->string('role')->default('member'); // member, leader, viewer
      $table->timestamps();

      $table->unique(['project_id', 'user_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('project_member');
    Schema::dropIfExists('project_tag');
    Schema::dropIfExists('project_tags');
  }
};
