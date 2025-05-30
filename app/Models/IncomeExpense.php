<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class IncomeExpense extends Model
{
    /** @use HasFactory<\Database\Factories\IncomeExpenseFactory> */
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'type',
        'date',
        'category',
        'user_id'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
