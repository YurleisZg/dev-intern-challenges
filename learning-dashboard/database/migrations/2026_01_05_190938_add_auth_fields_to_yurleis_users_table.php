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
        if (!Schema::hasTable('yurleis_users')) {
            Schema::create('yurleis_users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });

            return;
        }

        Schema::table('yurleis_users', function (Blueprint $table) {
            if (!Schema::hasColumn('yurleis_users', 'name')) {
                $table->string('name');
            }

            if (!Schema::hasColumn('yurleis_users', 'email')) {
                $table->string('email')->unique();
            }

            if (!Schema::hasColumn('yurleis_users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }

            if (!Schema::hasColumn('yurleis_users', 'password')) {
                $table->string('password');
            }

            if (!Schema::hasColumn('yurleis_users', 'remember_token')) {
                $table->rememberToken();
            }

            if (!Schema::hasColumn('yurleis_users', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yurleis_users');
    }
};
