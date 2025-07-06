<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'reference_number',
        'description',
        'type',
        'amount',
        'status',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function accounts()
    {
        return $this->belongsToMany(FinancialAccount::class, 'transaction_details', 'transaction_id', 'account_id')
                    ->withPivot(['debit', 'credit', 'description'])
                    ->withTimestamps();
    }
} 