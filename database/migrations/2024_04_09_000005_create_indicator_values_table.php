<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicator_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained('financial_indicators');
            $table->date('date');
            $table->decimal('value', 15, 2);
            $table->json('calculation_data')->nullable();
            $table->string('status')->default('calculated');
            $table->timestamps();
            
            // Ensure we don't have duplicate values for the same indicator and date
            $table->unique(['indicator_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicator_values');
    }
}; 