<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CryptoTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'transaction_hash',
        'currency',
        'type',
        'amount',
        'fee',
        'from_address',
        'to_address',
        'status',
        'confirmations',
        'confirmed_at',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'fee' => 'decimal:8',
        'confirmations' => 'integer',
        'confirmed_at' => 'datetime',
        'metadata' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(CryptoWallet::class, 'wallet_id');
    }

    public function currencyInfo()
    {
        return $this->belongsTo(CryptoCurrency::class, 'currency', 'symbol');
    }

    public function getAmountInUsdAttribute()
    {
        $currency = $this->currencyInfo;
        return $currency ? $this->amount * $currency->current_price_usd : 0;
    }

    public function getAmountInEurAttribute()
    {
        $currency = $this->currencyInfo;
        return $currency ? $this->amount * $currency->current_price_eur : 0;
    }

    public function getFeeInUsdAttribute()
    {
        $currency = $this->currencyInfo;
        return $currency ? $this->fee * $currency->current_price_usd : 0;
    }

    public function getFeeInEurAttribute()
    {
        $currency = $this->currencyInfo;
        return $currency ? $this->fee * $currency->current_price_eur : 0;
    }
} 