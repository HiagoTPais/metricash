<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crypto_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->unique(); // BTC, ETH, USDT, etc.
            $table->string('name'); // Bitcoin, Ethereum, Tether, etc.
            $table->string('icon')->nullable(); // Icon URL or SVG
            $table->decimal('current_price_usd', 18, 8)->default(0);
            $table->decimal('current_price_eur', 18, 8)->default(0);
            $table->decimal('market_cap', 18, 2)->default(0);
            $table->decimal('volume_24h', 18, 2)->default(0);
            $table->decimal('price_change_24h', 10, 2)->default(0);
            $table->decimal('price_change_percentage_24h', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Additional currency info
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crypto_currencies');
    }
}; 