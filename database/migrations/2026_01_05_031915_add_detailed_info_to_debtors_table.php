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
        Schema::table('debtors', function (Blueprint $table) {
            // Debtor type
            $table->enum('debtor_type', ['individual', 'company'])->default('individual')->after('user_id');
            
            // Individual/Staff fields
            $table->string('staff_number')->nullable()->after('debtor_type');
            $table->string('ic_number')->nullable()->after('staff_number');
            $table->string('phone_number')->nullable()->after('ic_number');
            $table->text('address')->nullable()->after('phone_number');
            $table->string('position')->nullable()->after('address');
            $table->date('start_working_date')->nullable()->after('position');
            $table->date('resign_date')->nullable()->after('start_working_date');
            
            // Company fields
            $table->string('ssm_number')->nullable()->after('resign_date');
            $table->string('office_phone')->nullable()->after('ssm_number');
            $table->text('company_address')->nullable()->after('office_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('debtors', function (Blueprint $table) {
            $table->dropColumn([
                'debtor_type',
                'staff_number',
                'ic_number',
                'phone_number',
                'address',
                'position',
                'start_working_date',
                'resign_date',
                'ssm_number',
                'office_phone',
                'company_address',
            ]);
        });
    }
};
