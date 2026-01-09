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
        Schema::create('elkin_salary_records_details', function (Blueprint $table) {
            $table->id('detail_id');
            $table->unsignedBigInteger('record_id');
            $table->date('shift_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->foreign('record_id')
                ->references('record_id')
                ->on('elkin_salary_records')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elkin_salary_records_details');
    }
};
