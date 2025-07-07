<?php

namespace App\Http\Controllers;

use App\Models\CryptoWallet;
use App\Models\CryptoTransaction;
use App\Models\CryptoCurrency;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Services\BitcoinService;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CryptoController extends Controller
{
    protected BitcoinService $walletService;

    public function __construct(BitcoinService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Get user's wallets with currency info
        $wallets = $user->cryptoWallets()
            ->with('currencyInfo')
            ->where('is_active', true)
            ->get();

        // Get recent transactions
        $recentTransactions = $user->cryptoTransactions()
            ->with(['wallet', 'currencyInfo'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get supported currencies
        $currencies = CryptoCurrency::where('is_active', true)->get();

        // Calculate total balance in USD
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

    public function createWallet(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'currency' => 'required|string|exists:crypto_currencies,symbol',
        ]);

        $user = Auth::user();

        $address = $this->generateMockAddress($request->currency);

        $wallet = $user->cryptoWallets()->create([
            'name' => $request->name,
            'currency' => $request->currency,
            'address' => $address,
            'balance' => 0,
            'is_active' => true,
        ]);

        $wallet = $this->walletService->createWallet();
        return response()->json([
            'success' => true,
            'wallet' => $wallet
        ]);

        return redirect()->back()->with('success', 'Wallet created successfully!');
    }

    public function sendCrypto(Request $request)
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

    private function generateMockAddress($currency)
    {
        $prefixes = [
            'BTC' => 'bc1',
            'ETH' => '0x',
            'USDT' => '0x',
            'LTC' => 'ltc1',
            'BCH' => 'bitcoincash:',
        ];

        $prefix = $prefixes[$currency] ?? '0x';
        $randomPart = Str::random(32);

        return $prefix . $randomPart;
    }

    private function generateTransactionHash()
    {
        return '0x' . Str::random(64);
    }

    private function generateQRCode($address)
    {
        // In a real app, you would use a QR code library
        // For now, we'll return a placeholder
        return "data:image/svg+xml;base64," . base64_encode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect width="200" height="200" fill="white"/><text x="100" y="100" text-anchor="middle" font-size="12">' . $address . '</text></svg>'
        );
    }
}
