<?php

namespace App\Http\Controllers;

use App\Models\IncomeExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IncomeExpenseController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = IncomeExpense::query();

        if ($request->has('type') && $request->type !== null) {
            Log::info("TESTE TYPE");
            $query->where('type', $request->type);
        }

        if ($request->has('category') && $request->category !== null) {
            Log::info("TESTE CATEGORIA");
            $query->where('category', $request->category);
        }

        if ($request->has('description') && $request->description !== null) {
            Log::info("TESTE DESCRICAO");
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if (
            $request->has('startDate') && $request->has('endDate') &&
            $request->startDate !== null && $request->endDate !== null
        ) {
            Log::info("TESTE STARTDATE");
            $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $incomeExpenses = $query->paginate(10);

        $filteredQuery = clone $query;
        $totalIncome = (clone $filteredQuery)->where('type', 'income')->sum('amount');
        $totalExpenses = (clone $filteredQuery)->where('type', 'expense')->sum('amount');

        $balance = number_format($totalIncome - $totalExpenses, 2, '.', '');

        return inertia('IncomeExpenses/Index', [
            'incomeExpenses' => $incomeExpenses,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'balance' => $balance
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'category' => 'required|string|max:255'
        ]);

        $incomeExpense = auth()->user()->incomeExpenses()->create($validated);

        return redirect()->route('income-expenses.index')->with('success', 'Record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomeExpense $incomeExpense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomeExpense $incomeExpense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomeExpense $incomeExpense)
    {
        $this->authorize('update', $incomeExpense);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'category' => 'required|string|max:255'
        ]);

        $validated['user_id'] = auth()->id();

        $incomeExpense->update($validated);

        return redirect()->route('income-expenses.index')->with('success', 'Record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomeExpense $incomeExpense)
    {
        $this->authorize('delete', $incomeExpense);

        $incomeExpense->delete();

        return redirect()->route('income-expenses.index')->with('success', 'Record deleted successfully.');
    }
}
