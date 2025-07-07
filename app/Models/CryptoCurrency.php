<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoCurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'icon',
        'current_price_usd',
        'current_price_eur',
        'market_cap',
        'volume_24h',
        'price_change_24h',
        'price_change_percentage_24h',
        'is_active',
        'metadata'
    ];

    protected $casts = [
        'current_price_usd' => 'decimal:8',
        'current_price_eur' => 'decimal:8',
        'market_cap' => 'decimal:2',
        'volume_24h' => 'decimal:2',
        'price_change_24h' => 'decimal:2',
        'price_change_percentage_24h' => 'decimal:2',
        'is_active' => 'boolean',
        'metadata' => 'array'
    ];

    public function wallets()
    {
        return $this->hasMany(CryptoWallet::class, 'currency', 'symbol');
    }

    public function transactions()
    {
        return $this->hasMany(CryptoTransaction::class, 'currency', 'symbol');
    }

    public function getFormattedPriceUsdAttribute()
    {
        return number_format($this->current_price_usd, 2);
    }

    public function getFormattedPriceEurAttribute()
    {
        return number_format($this->current_price_eur, 2);
    }

    public function getFormattedMarketCapAttribute()
    {
        if ($this->market_cap >= 1e9) {
            return number_format($this->market_cap / 1e9, 2) . 'B';
        } elseif ($this->market_cap >= 1e6) {
            return number_format($this->market_cap / 1e6, 2) . 'M';
        } elseif ($this->market_cap >= 1e3) {
            return number_format($this->market_cap / 1e3, 2) . 'K';
        }
        return number_format($this->market_cap, 2);
    }

    public function getPriceChangeColorAttribute()
    {
        return $this->price_change_percentage_24h >= 0 ? 'text-green-600' : 'text-red-600';
    }
} 