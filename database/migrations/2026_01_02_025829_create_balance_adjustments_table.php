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
        Schema::create('balance_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debtor_id')->constrained()->cascadeOnDelete()->index();
            $table->decimal('amount', 12, 2);
            $table->text('note')->nullable();
            $table->dateTime('adjusted_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['debtor_id', 'adjusted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_adjustments');
    }
};
