<?php

namespace App\Http\Controllers;

use App\Models\CryptoWallet;
use App\Models\CryptoTransaction;
use App\Models\CryptoCurrency;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CryptoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $wallets = $user->cryptoWallets()
            ->with('currencyInfo')
            ->where('is_active', true)
            ->get();

        $recentTransactions = $user->cryptoTransactions()
            ->with(['wallet', 'currencyInfo'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $currencies = CryptoCurrency::where('is_active', true)->get();

        $totalBalanceUsd = $wallets->sum('balance_in_usd');
        $totalBalanceEur = $wallets->sum('balance_in_eur');

        return Inertia::render('Crypto/Index', [
            'wallets' => $wallets,
            'recentTransactions' => $recentTransactions,
            'currencies' => $currencies,
            'totalBalanceUsd' => $totalBalanceUsd,
            'totalBalanceEur' => $totalBalanceEur,
        ]);
    }

    public function setCreatedWallet(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'currency' => 'required|string|exists:crypto_currencies,symbol',
        ]);

        $user = Auth::user();

        $wallet = $user->cryptoWallets()->create([
            'name' => $request->name,
            'currency' => $request->currency,
            'address' => $address,
            'balance' => 0,
            'is_active' => true,
        ]);

        // Optionally generate QR code for the address
        $qrCode = $this->generateQRCode($address);

        return response()->json([
            'success' => true,
            'wallet' => [
                'id' => $wallet->id,
                'name' => $wallet->name,
                'currency' => $wallet->currency,
                'address' => $wallet->address,
                'qr_code' => $qrCode,
            ]
        ]);
    }

    public function setSentCrypto(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:crypto_wallets,id',
            'to_address' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
            'fee' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $wallet = $user->cryptoWallets()->findOrFail($request->wallet_id);

        // Check if user has enough balance
        if ($wallet->balance < ($request->amount + $request->fee)) {
            return redirect()->back()->with('error', 'Insufficient balance!');
        }

        // Create transaction
        $transaction = $user->cryptoTransactions()->create([
            'wallet_id' => $wallet->id,
            'transaction_hash' => $this->generateTransactionHash(),
            'currency' => $wallet->currency,
            'type' => 'send',
            'amount' => $request->amount,
            'fee' => $request->fee,
            'from_address' => $wallet->address,
            'to_address' => $request->to_address,
            'status' => 'pending',
        ]);

        // Update wallet balance
        $wallet->decrement('balance', $request->amount + $request->fee);

        return redirect()->back()->with('success', 'Transaction sent successfully!');
    }

    public function getTransactionHistory(Request $request)
    {
        $user = Auth::user();

        $query = $user->cryptoTransactions()->with(['wallet', 'currencyInfo']);

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('currency')) {
            $query->where('currency', $request->currency);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($transactions);
    }

    public function transactionsPage(Request $request)
    {
        $currencies = CryptoCurrency::where('is_active', true)->get();

        return Inertia::render('Crypto/Transactions', [
            'currencies' => $currencies,
        ]);
    }

    public function convertCurrency(Request $request)
    {
        $request->validate([
            'from_currency' => 'required|string|exists:crypto_currencies,symbol',
            'to_currency' => 'required|string|exists:crypto_currencies,symbol',
            'amount' => 'required|numeric|min:0',
        ]);

        $fromCurrency = CryptoCurrency::where('symbol', $request->from_currency)->first();
        $toCurrency = CryptoCurrency::where('symbol', $request->to_currency)->first();

        if (!$fromCurrency || !$toCurrency) {
            return response()->json(['error' => 'Currency not found'], 404);
        }

        // Convert through USD
        $amountInUsd = $request->amount * $fromCurrency->current_price_usd;
        $convertedAmount = $amountInUsd / $toCurrency->current_price_usd;

        return response()->json([
            'from_currency' => $request->from_currency,
            'to_currency' => $request->to_currency,
            'from_amount' => $request->amount,
            'to_amount' => $convertedAmount,
            'rate' => $fromCurrency->current_price_usd / $toCurrency->current_price_usd,
        ]);
    }

    public function getWalletAddress(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:crypto_wallets,id',
        ]);

        $user = Auth::user();
        $wallet = $user->cryptoWallets()->findOrFail($request->wallet_id);

        return response()->json([
            'address' => $wallet->address,
            'currency' => $wallet->currency,
            'qr_code' => $this->generateQRCode($wallet->address),
        ]);
    }
}
