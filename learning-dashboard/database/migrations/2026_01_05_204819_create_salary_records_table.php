<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
  {
    Schema::create('salary_records', function (Blueprint $table) {
      $table->id();

      $table->foreignId('yurleis_user_id')
        ->constrained('yurleis_users')
        ->cascadeOnDelete();

      $table->decimal('gross_salary', 12, 2);

      $table->decimal('tax', 12, 2)->default(0);
      $table->decimal('health', 12, 2)->default(0);
      $table->decimal('bonus', 12, 2)->default(300);
      $table->decimal('base_net', 12, 2)->default(0);

      $table->decimal('overtime_total', 12, 2)->default(0);
      $table->decimal('grand_total', 12, 2)->default(0);

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('salary_records');
  }
};
