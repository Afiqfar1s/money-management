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
        Schema::table('balance_adjustments', function (Blueprint $table) {
            $table->string('voucher_no')->nullable()->unique()->after('debtor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('balance_adjustments', function (Blueprint $table) {
            $table->dropColumn('voucher_no');
        });
    }
};
