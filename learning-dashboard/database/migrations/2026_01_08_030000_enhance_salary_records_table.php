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
        // Add additional columns to salary_records if needed for future enhancements
        if (!Schema::hasColumn('elkin_salary_records', 'calculated_data')) {
            Schema::table('elkin_salary_records', function (Blueprint $table) {
                $table->json('calculated_data')->nullable()->after('status')->comment('Stores the full calculation result as JSON');
                $table->decimal('total_net_salary', 12, 2)->nullable()->after('gross_salary_input')->comment('Total net salary calculated');
                $table->decimal('total_overtime', 12, 2)->nullable()->after('total_net_salary')->comment('Total overtime amount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('elkin_salary_records', 'calculated_data')) {
            Schema::table('elkin_salary_records', function (Blueprint $table) {
                $table->dropColumn(['calculated_data', 'total_net_salary', 'total_overtime']);
            });
        }
    }
};
