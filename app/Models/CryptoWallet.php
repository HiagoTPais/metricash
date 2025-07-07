<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CryptoWallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'currency',
        'address',
        'private_key',
        'balance',
        'is_active',
        'metadata'
    ];

    protected $casts = [
        'balance' => 'decimal:8',
        'is_active' => 'boolean',
        'metadata' => 'array'
    ];

    protected $hidden = [
        'private_key'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(CryptoTransaction::class, 'wallet_id');
    }

    public function currencyInfo()
    {
        return $this->belongsTo(CryptoCurrency::class, 'currency', 'symbol');
    }

    public function getBalanceInUsdAttribute()
    {
        $currency = $this->currencyInfo;
        return $currency ? $this->balance * $currency->current_price_usd : 0;
    }

    public function getBalanceInEurAttribute()
    {
        $currency = $this->currencyInfo;
        return $currency ? $this->balance * $currency->current_price_eur : 0;
    }
} 