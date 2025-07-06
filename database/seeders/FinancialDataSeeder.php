<?php

namespace Database\Seeders;

use App\Models\FinancialAccount;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\IncomeExpense;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FinancialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create financial accounts
        $accounts = [
            [
                'name' => 'Cash',
                'code' => '1000',
                'type' => 'asset',
                'description' => 'Cash on hand',
                'is_active' => true,
            ],
            [
                'name' => 'Bank Account',
                'code' => '1010',
                'type' => 'asset',
                'description' => 'Main bank account',
                'is_active' => true,
            ],
            [
                'name' => 'Accounts Receivable',
                'code' => '1100',
                'type' => 'asset',
                'description' => 'Money owed by customers',
                'is_active' => true,
            ],
            [
                'name' => 'Equipment',
                'code' => '1500',
                'type' => 'asset',
                'description' => 'Office equipment',
                'is_active' => true,
            ],
            [
                'name' => 'Accounts Payable',
                'code' => '2000',
                'type' => 'liability',
                'description' => 'Money owed to suppliers',
                'is_active' => true,
            ],
            [
                'name' => 'Owner Equity',
                'code' => '3000',
                'type' => 'equity',
                'description' => 'Owner investment',
                'is_active' => true,
            ],
            [
                'name' => 'Revenue',
                'code' => '4000',
                'type' => 'revenue',
                'description' => 'Sales revenue',
                'is_active' => true,
            ],
            [
                'name' => 'Cost of Goods Sold',
                'code' => '5000',
                'type' => 'expense',
                'description' => 'Direct costs',
                'is_active' => true,
            ],
            [
                'name' => 'Operating Expenses',
                'code' => '6000',
                'type' => 'expense',
                'description' => 'General expenses',
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $accountData) {
            FinancialAccount::create($accountData);
        }

        // Get user
        $user = User::first();

        // Create sample transactions for the last 6 months
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now();

        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            // Create 1-3 transactions per day
            $numTransactions = rand(1, 3);
            
            for ($i = 0; $i < $numTransactions; $i++) {
                $transaction = Transaction::create([
                    'date' => $date->format('Y-m-d'),
                    'reference_number' => 'TXN-' . $date->format('Ymd') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'description' => $this->getRandomDescription(),
                    'type' => rand(0, 1) ? 'income' : 'expense',
                    'amount' => rand(100, 5000),
                    'status' => 'completed',
                    'created_by' => $user->id,
                ]);

                // Create transaction details
                $this->createTransactionDetails($transaction);
            }
        }

        // Create additional income/expense records for better data
        $categories = ['Salary', 'Freelance', 'Investment', 'Rent', 'Utilities', 'Food', 'Transport', 'Entertainment'];
        
        for ($i = 0; $i < 100; $i++) {
            $date = Carbon::now()->subDays(rand(0, 180));
            $type = rand(0, 1) ? 'income' : 'expense';
            $amount = $type === 'income' ? rand(500, 5000) : rand(50, 1000);
            
            IncomeExpense::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'description' => $this->getRandomDescription(),
                'type' => $type,
                'category' => $categories[array_rand($categories)],
                'date' => $date->format('Y-m-d'),
            ]);
        }
    }

    private function getRandomDescription()
    {
        $descriptions = [
            'Office supplies purchase',
            'Client payment received',
            'Utility bill payment',
            'Equipment maintenance',
            'Consulting fee',
            'Marketing expenses',
            'Travel expenses',
            'Software subscription',
            'Insurance payment',
            'Tax payment',
            'Salary payment',
            'Freelance work',
            'Investment income',
            'Rent payment',
            'Food and dining',
            'Transportation costs',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function createTransactionDetails($transaction)
    {
        $accounts = FinancialAccount::all();
        
        if ($transaction->type === 'income') {
            // Debit cash/bank, credit revenue
            $assetAccount = $accounts->where('type', 'asset')->random();
            $revenueAccount = $accounts->where('type', 'revenue')->first();
            
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'account_id' => $assetAccount->id,
                'debit' => $transaction->amount,
                'credit' => 0,
                'description' => 'Cash received',
            ]);
            
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'account_id' => $revenueAccount->id,
                'debit' => 0,
                'credit' => $transaction->amount,
                'description' => 'Revenue earned',
            ]);
        } else {
            // Debit expense, credit cash/bank
            $expenseAccount = $accounts->where('type', 'expense')->random();
            $assetAccount = $accounts->where('type', 'asset')->random();
            
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'account_id' => $expenseAccount->id,
                'debit' => $transaction->amount,
                'credit' => 0,
                'description' => 'Expense incurred',
            ]);
            
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'account_id' => $assetAccount->id,
                'debit' => 0,
                'credit' => $transaction->amount,
                'description' => 'Cash paid',
            ]);
        }
    }
} 