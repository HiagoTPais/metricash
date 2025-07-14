<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\IncomeExpense;
use Carbon\Carbon;

class CurrencyController extends Controller
{
    /**
     * Fetch currency data from CoinGecko and return as JSON.
     */
    public function index()
    {
        $data = Cache::remember('currencies_data', 300, function () {
            $response = Http::get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => 'bitcoin,ethereum,usd,euro',
                'vs_currencies' => '*',
                'include_24hr_change' => 'true',
                'include_market_cap' => 'true',
            ]);

            if ($response->successful()) {
                return $response->json();
            }
            return [];
        });
        return response()->json($data);
    }

    /**
     * Return dashboard stats: total balance, monthly income, monthly expenses, profit margin.
     */
    public function dashboardStats()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Monthly Income
        $monthlyIncome = IncomeExpense::where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Monthly Expenses
        $monthlyExpenses = IncomeExpense::where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Total Income (all time)
        $totalIncome = IncomeExpense::where('type', 'income')->sum('amount');
        // Total Expenses (all time)
        $totalExpenses = IncomeExpense::where('type', 'expense')->sum('amount');
        // Total Balance
        $totalBalance = $totalIncome - $totalExpenses;

        // Profit Margin (monthly)
        $profitMargin = $monthlyIncome > 0 ? (($monthlyIncome - $monthlyExpenses) / $monthlyIncome) * 100 : 0;

        return response()->json([
            'total_balance' => $totalBalance,
            'monthly_income' => $monthlyIncome,
            'monthly_expenses' => $monthlyExpenses,
            'profit_margin' => $profitMargin,
        ]);
    }
} 