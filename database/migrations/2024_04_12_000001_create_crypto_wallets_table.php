<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crypto_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('currency'); // BTC, ETH, USDT, etc.
            $table->string('address')->unique();
            $table->string('private_key')->nullable(); // Encrypted
            $table->decimal('balance', 18, 8)->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Additional wallet info
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crypto_wallets');
    }
}; 