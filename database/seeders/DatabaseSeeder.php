<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\IncomeExpense;
use Database\Seeders\FinancialDataSeeder;
use Database\Seeders\CryptoDataSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        IncomeExpense::factory(40)->create();
        
        // Seed financial data for indicators
        $this->call(FinancialDataSeeder::class);
        
        // Seed crypto data
        $this->call(CryptoDataSeeder::class);
    }
}
