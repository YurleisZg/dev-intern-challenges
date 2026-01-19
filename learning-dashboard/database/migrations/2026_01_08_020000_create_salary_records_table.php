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
        Schema::create('elkin_salary_records', function (Blueprint $table) {
            $table->id('record_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('gross_salary_input', 15, 2);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('elkin_users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elkin_salary_records');
    }
};
