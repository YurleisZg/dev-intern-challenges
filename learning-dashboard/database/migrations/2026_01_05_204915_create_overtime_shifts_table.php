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
          Schema::create('overtime_shifts', function (Blueprint $table) {
      $table->id();

      $table->foreignId('salary_record_id')
        ->constrained('salary_records')
        ->cascadeOnDelete();

      $table->date('date');
      $table->time('start_time');
      $table->time('end_time');

      // calculados
      $table->unsignedInteger('overtime_minutes')->default(0);
      $table->unsignedInteger('night_overtime_minutes')->default(0);
      $table->boolean('is_sunday')->default(false);

      $table->decimal('hourly_rate', 12, 4)->default(0);
      $table->decimal('multiplier', 12, 4)->default(1);
      $table->decimal('total', 12, 2)->default(0);

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('overtime_shifts');
  }
};
