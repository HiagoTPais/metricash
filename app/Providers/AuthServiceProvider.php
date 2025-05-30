<?php

namespace App\Providers;

use App\Models\IncomeExpense;
use App\Policies\IncomeExpensePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        IncomeExpense::class => IncomeExpensePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
} 