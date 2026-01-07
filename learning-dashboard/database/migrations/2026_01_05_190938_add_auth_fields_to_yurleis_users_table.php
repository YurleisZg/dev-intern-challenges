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
        Schema::table('yurleis_users', function (Blueprint $table) {
              if (!Schema::hasColumn('yurleis_users', 'name')) {
                $table->string('name')->nullable(); // quita nullable si puedes
            }

            if (!Schema::hasColumn('yurleis_users', 'email')) {
                $table->string('email')->unique()->nullable();
            }

            if (!Schema::hasColumn('yurleis_users', 'password')) {
                $table->string('password')->nullable();
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
        Schema::table('yurleis_users', function (Blueprint $table) {
            //
        });
    }
};
