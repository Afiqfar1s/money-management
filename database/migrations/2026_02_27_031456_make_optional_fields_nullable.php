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
        // Make description nullable in debtors table
        Schema::table('debtors', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        // Make voucher_no and note nullable in payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->string('voucher_no')->nullable()->change();
            $table->text('note')->nullable()->change();
        });

        // Make voucher_no and note nullable in balance_adjustments table
        Schema::table('balance_adjustments', function (Blueprint $table) {
            $table->string('voucher_no')->nullable()->change();
            $table->text('note')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert description to non-nullable in debtors table
        Schema::table('debtors', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });

        // Revert voucher_no and note to non-nullable in payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->string('voucher_no')->nullable(false)->change();
            $table->text('note')->nullable(false)->change();
        });

        // Revert voucher_no and note to non-nullable in balance_adjustments table
        Schema::table('balance_adjustments', function (Blueprint $table) {
            $table->string('voucher_no')->nullable(false)->change();
            $table->text('note')->nullable(false)->change();
        });
    }
};
