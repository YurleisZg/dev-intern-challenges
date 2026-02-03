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
        Schema::create('yurleis_toggle_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('yurleis_user_id')->constrained('yurleis_users')->onDelete('cascade');
            $table->tinyInteger('stage')->default(1);
            $table->enum('status', ['active', 'completed', 'failed', 'abandoned'])->default('active');
            $table->text('stage1_data')->nullable(); 
            $table->text('stage2_data')->nullable(); 
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['yurleis_user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yurleis_toggle_games');
    }
};
