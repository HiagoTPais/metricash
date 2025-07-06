<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use App\Models\Transaction;
use App\Models\IncomeExpense;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IndicatorsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // Convert string dates to Carbon instances if needed
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        // Get account balances
        $accountBalances = FinancialAccount::where('is_active', true)
            ->where('type', 'asset')
            ->with(['transactionDetails' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                });
            }])
            ->get()
            ->map(function ($account) {
                return [
                    'id' => $account->id,
                    'name' => $account->name,
                    'code' => $account->code,
                    'type' => $account->type,
                    'balance' => $account->balance,
                ];
            });

        // Get income vs expenses data
        $incomeExpensesData = $this->getIncomeExpensesData($startDate, $endDate, $period);

        // Get projected cash flow
        $projectedCashFlow = $this->getProjectedCashFlow($startDate, $endDate);

        // Get profit/loss
        $profitLoss = $this->getProfitLoss($startDate, $endDate);

        // Get change in equity
        $equityChange = $this->getEquityChange($startDate, $endDate);

        return Inertia::render('Indicators/Index', [
            'accountBalances' => $accountBalances,
            'incomeExpensesData' => $incomeExpensesData,
            'projectedCashFlow' => $projectedCashFlow,
            'profitLoss' => $profitLoss,
            'equityChange' => $equityChange,
            'period' => $period,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }

    private function getIncomeExpensesData($startDate, $endDate, $period)
    {
        $query = IncomeExpense::whereBetween('date', [$startDate, $endDate]);

        if ($period === 'monthly') {
            $data = $query->selectRaw('
                DATE_FORMAT(date, "%Y-%m") as period,
                type,
                SUM(amount) as total
            ')
            ->groupBy('period', 'type')
            ->orderBy('period')
            ->get();
        } else {
            $data = $query->selectRaw('
                YEAR(date) as period,
                type,
                SUM(amount) as total
            ')
            ->groupBy('period', 'type')
            ->orderBy('period')
            ->get();
        }

        $formattedData = [];
        foreach ($data as $item) {
            if (!isset($formattedData[$item->period])) {
                $formattedData[$item->period] = [
                    'period' => $item->period,
                    'income' => 0,
                    'expenses' => 0,
                    'net' => 0
                ];
            }
            
            if ($item->type === 'income') {
                $formattedData[$item->period]['income'] = $item->total;
            } else {
                $formattedData[$item->period]['expenses'] = $item->total;
            }
            
            $formattedData[$item->period]['net'] = 
                $formattedData[$item->period]['income'] - $formattedData[$item->period]['expenses'];
        }

        return array_values($formattedData);
    }

    private function getProjectedCashFlow($startDate, $endDate)
    {
        // Get historical cash flow for projection
        $historicalData = IncomeExpense::whereBetween('date', [
            $startDate->copy()->subMonths(6),
            $endDate
        ])
        ->selectRaw('
            DATE_FORMAT(date, "%Y-%m") as month,
            type,
            SUM(amount) as total
        ')
        ->groupBy('month', 'type')
        ->orderBy('month')
        ->get();

        $monthlyData = [];
        foreach ($historicalData as $item) {
            if (!isset($monthlyData[$item->month])) {
                $monthlyData[$item->month] = [
                    'income' => 0,
                    'expenses' => 0
                ];
            }
            
            if ($item->type === 'income') {
                $monthlyData[$item->month]['income'] = $item->total;
            } else {
                $monthlyData[$item->month]['expenses'] = $item->total;
            }
        }

        // Calculate average monthly income and expenses
        $avgIncome = collect($monthlyData)->avg('income');
        $avgExpenses = collect($monthlyData)->avg('expenses');

        // Project next 3 months
        $projections = [];
        for ($i = 1; $i <= 3; $i++) {
            $projectionDate = $endDate->copy()->addMonths($i);
            $projections[] = [
                'month' => $projectionDate->format('Y-m'),
                'projected_income' => $avgIncome,
                'projected_expenses' => $avgExpenses,
                'projected_net' => $avgIncome - $avgExpenses
            ];
        }

        return [
            'historical' => array_values($monthlyData),
            'projections' => $projections,
            'avg_income' => $avgIncome,
            'avg_expenses' => $avgExpenses
        ];
    }

    private function getProfitLoss($startDate, $endDate)
    {
        $income = IncomeExpense::where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $expenses = IncomeExpense::where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $netProfit = $income - $expenses;
        $profitMargin = $income > 0 ? ($netProfit / $income) * 100 : 0;

        return [
            'income' => $income,
            'expenses' => $expenses,
            'net_profit' => $netProfit,
            'profit_margin' => $profitMargin,
            'is_profitable' => $netProfit > 0
        ];
    }

    private function getEquityChange($startDate, $endDate)
    {
        // Get equity accounts
        $equityAccounts = FinancialAccount::where('type', 'equity')
            ->where('is_active', true)
            ->get();

        $equityChange = 0;
        foreach ($equityAccounts as $account) {
            $debits = $account->transactionDetails()
                ->whereHas('transaction', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->sum('debit');

            $credits = $account->transactionDetails()
                ->whereHas('transaction', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                })
                ->sum('credit');

            // For equity accounts, credits increase equity, debits decrease
            $equityChange += ($credits - $debits);
        }

        return [
            'change' => $equityChange,
            'is_positive' => $equityChange > 0
        ];
    }
} 