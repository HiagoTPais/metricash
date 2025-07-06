<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
        'is_active',
        'parent_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(FinancialAccount::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(FinancialAccount::class, 'parent_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'account_id');
    }

    public function getBalanceAttribute()
    {
        $debits = $this->transactionDetails()->sum('debit');
        $credits = $this->transactionDetails()->sum('credit');
        
        // For asset and expense accounts, balance = debits - credits
        // For liability, equity, and revenue accounts, balance = credits - debits
        if (in_array($this->type, ['asset', 'expense'])) {
            return $debits - $credits;
        } else {
            return $credits - $debits;
        }
    }
} 