<?php

namespace Database\Seeders;

use App\Models\CryptoCurrency;
use App\Models\CryptoWallet;
use App\Models\CryptoTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CryptoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create supported cryptocurrencies
        $currencies = [
            [
                'symbol' => 'BTC',
                'name' => 'Bitcoin',
                'current_price_usd' => 45000.00,
                'current_price_eur' => 42000.00,
                'market_cap' => 850000000000,
                'volume_24h' => 25000000000,
                'price_change_24h' => 1250.00,
                'price_change_percentage_24h' => 2.85,
                'is_active' => true,
            ],
            [
                'symbol' => 'ETH',
                'name' => 'Ethereum',
                'current_price_usd' => 3200.00,
                'current_price_eur' => 3000.00,
                'market_cap' => 380000000000,
                'volume_24h' => 15000000000,
                'price_change_24h' => 85.00,
                'price_change_percentage_24h' => 2.73,
                'is_active' => true,
            ],
            [
                'symbol' => 'USDT',
                'name' => 'Tether',
                'current_price_usd' => 1.00,
                'current_price_eur' => 0.93,
                'market_cap' => 85000000000,
                'volume_24h' => 50000000000,
                'price_change_24h' => 0.00,
                'price_change_percentage_24h' => 0.00,
                'is_active' => true,
            ],
            [
                'symbol' => 'LTC',
                'name' => 'Litecoin',
                'current_price_usd' => 150.00,
                'current_price_eur' => 140.00,
                'market_cap' => 10000000000,
                'volume_24h' => 800000000,
                'price_change_24h' => 3.50,
                'price_change_percentage_24h' => 2.39,
                'is_active' => true,
            ],
            [
                'symbol' => 'BCH',
                'name' => 'Bitcoin Cash',
                'current_price_usd' => 280.00,
                'current_price_eur' => 260.00,
                'market_cap' => 5500000000,
                'volume_24h' => 300000000,
                'price_change_24h' => 8.00,
                'price_change_percentage_24h' => 2.94,
                'is_active' => true,
            ],
        ];

        foreach ($currencies as $currencyData) {
            CryptoCurrency::create($currencyData);
        }

        // Get user
        $user = User::first();

        // Create sample wallets
        $walletData = [
            [
                'name' => 'Bitcoin Wallet',
                'currency' => 'BTC',
                'balance' => 0.5,
            ],
            [
                'name' => 'Ethereum Wallet',
                'currency' => 'ETH',
                'balance' => 2.5,
            ],
            [
                'name' => 'Tether Wallet',
                'currency' => 'USDT',
                'balance' => 1000.00,
            ],
        ];

        foreach ($walletData as $data) {
            $wallet = $user->cryptoWallets()->create([
                'name' => $data['name'],
                'currency' => $data['currency'],
                'address' => $this->generateMockAddress($data['currency']),
                'balance' => $data['balance'],
                'is_active' => true,
            ]);

            // Create some sample transactions for each wallet
            $this->createSampleTransactions($user, $wallet);
        }
    }

    private function generateMockAddress($currency)
    {
        $prefixes = [
            'BTC' => 'bc1',
            'ETH' => '0x',
            'USDT' => '0x',
            'LTC' => 'ltc1',
            'BCH' => 'bitcoincash:',
        ];

        $prefix = $prefixes[$currency] ?? '0x';
        $randomPart = Str::random(32);
        
        return $prefix . $randomPart;
    }

    private function createSampleTransactions($user, $wallet)
    {
        $transactionTypes = ['send', 'receive'];
        $statuses = ['pending', 'confirmed', 'failed'];
        
        for ($i = 0; $i < rand(5, 15); $i++) {
            $type = $transactionTypes[array_rand($transactionTypes)];
            $amount = $type === 'receive' ? rand(100, 10000) / 1000000 : rand(10, 1000) / 1000000;
            $fee = rand(1, 100) / 1000000;
            
            $user->cryptoTransactions()->create([
                'wallet_id' => $wallet->id,
                'transaction_hash' => '0x' . Str::random(64),
                'currency' => $wallet->currency,
                'type' => $type,
                'amount' => $amount,
                'fee' => $fee,
                'from_address' => $type === 'send' ? $wallet->address : $this->generateMockAddress($wallet->currency),
                'to_address' => $type === 'receive' ? $wallet->address : $this->generateMockAddress($wallet->currency),
                'status' => $statuses[array_rand($statuses)],
                'confirmations' => rand(0, 12),
                'confirmed_at' => Carbon::now()->subDays(rand(0, 30)),
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);
        }
    }
} 