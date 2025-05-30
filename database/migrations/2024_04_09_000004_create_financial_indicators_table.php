<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['ratio', 'amount', 'percentage', 'count']);
            $table->string('calculation_method');
            $table->json('parameters')->nullable();
            $table->string('frequency')->default('monthly');
            $table->decimal('target_value', 15, 2)->nullable();
            $table->decimal('warning_threshold', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_indicators');
    }
}; 