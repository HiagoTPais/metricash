<?php

namespace App\Policies;

use App\Models\IncomeExpense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncomeExpensePolicy
{
    use HandlesAuthorization;

    public function update(User $user, IncomeExpense $incomeExpense)
    {        
        return $user->id === $incomeExpense->user_id;
    }

    public function delete(User $user, IncomeExpense $incomeExpense)
    {
        return $user->id === $incomeExpense->user_id;
    }
} 