<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crypto_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->nullable()->constrained('crypto_wallets')->onDelete('set null');
            $table->string('transaction_hash')->unique();
            $table->string('currency'); // BTC, ETH, USDT, etc.
            $table->enum('type', ['send', 'receive']);
            $table->decimal('amount', 18, 8);
            $table->decimal('fee', 18, 8)->default(0);
            $table->string('from_address')->nullable();
            $table->string('to_address');
            $table->string('status')->default('pending'); // pending, confirmed, failed
            $table->integer('confirmations')->default(0);
            $table->timestamp('confirmed_at')->nullable();
            $table->json('metadata')->nullable(); // Additional transaction info
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crypto_transactions');
    }
}; 