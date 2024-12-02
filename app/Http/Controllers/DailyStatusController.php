<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailyStatusResource;
use App\Models\DailyStatus;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DailyStatusController extends Controller
{
    public function index()
    {
        $daily_status = DailyStatus::orderBy('date', 'desc')->first();
        return Inertia::render('Admin/Dailystatus/index', ['dailystatus' => $daily_status]);
    }

    public function insertstatus($id)
    {
        $dailystatus = DailyStatus::findOrFail($id);
        $log_date = $dailystatus->date;
        $dailystatus->update([
            'status' => 'day_end',
        ]);
        $userWalletBalances = [];
        $transactionlogs = Transaction::whereDate('created_at', $log_date)
            ->where('status', 'Completed')
            ->get();
        // foreach ($transactionlogs as $transaction) {
        //     if (!isset($userWalletBalances[$transaction->user_id])) {
        //         $userWalletBalances[$transaction->user_id] = [];
        //     }
        //     if (!isset($userWalletBalances[$transaction->user_id][$transaction->from_currency_id])) {
        //         $userWalletBalances[$transaction->user_id][$transaction->from_currency_id] = 0;
        //     }
        //     $userWalletBalances[$transaction->user_id][$transaction->from_currency_id] += $transaction->from_amount;
        //     if (!isset($userWalletBalances[$transaction->user_id][$transaction->to_currency_id])) {
        //         $userWalletBalances[$transaction->user_id][$transaction->to_currency_id] = 0;
        //     }
        //     $userWalletBalances[$transaction->user_id][$transaction->to_currency_id] -= $transaction->to_amount;
        // }
        // foreach ($userWalletBalances as $userId => $wallets) {
        //     $id = $userId;
        //     foreach ($wallets as $walletId => $balance) {
        //         //dd($balance,$walletId);
        //         Wallet::create([
        //             'user_id' => $id,
        //             'currency_id' => $walletId,
        //             'balance' => $balance,
        //         ]);
        //     }
        // }
        $userWalletBalances = [];
        $userExchangeRates = []; // To store exchange rate totals and counts

        $transactionlogs = Transaction::whereDate('created_at', $log_date)
            ->where('status', 'Completed')
            ->get();

        foreach ($transactionlogs as $transaction) {
            $userId = $transaction->user_id;
            $fromCurrency = $transaction->from_currency_id;
            $toCurrency = $transaction->to_currency_id;
            $type = $transaction->type; // Either 'buy' or 'sell'
            $exchangeRate = $transaction->exchangerate;

            // Initialize wallet balances
            if (!isset($userWalletBalances[$userId])) {
                $userWalletBalances[$userId] = [];
            }
            if (!isset($userWalletBalances[$userId][$fromCurrency])) {
                $userWalletBalances[$userId][$fromCurrency] = 0;
            }
            if (!isset($userWalletBalances[$userId][$toCurrency])) {
                $userWalletBalances[$userId][$toCurrency] = 0;
            }

            // Update wallet balances
            $userWalletBalances[$userId][$fromCurrency] += $transaction->from_amount;
            $userWalletBalances[$userId][$toCurrency] -= $transaction->to_amount;

            // Initialize exchange rate tracking
            if (!isset($userExchangeRates[$userId])) {
                $userExchangeRates[$userId] = [];
            }
            if (!isset($userExchangeRates[$userId][$fromCurrency])) {
                $userExchangeRates[$userId][$fromCurrency] = [
                    'buy' => ['total' => 0, 'count' => 0],
                    'sell' => ['total' => 0, 'count' => 0],
                ];
            }

            // Update exchange rate totals and counts
            if ($type === 'buy') {
                $userExchangeRates[$userId][$fromCurrency]['buy']['total'] += $exchangeRate;
                $userExchangeRates[$userId][$fromCurrency]['buy']['count'] += 1;
            } elseif ($type === 'sell') {
                $userExchangeRates[$userId][$fromCurrency]['sell']['total'] += $exchangeRate;
                $userExchangeRates[$userId][$fromCurrency]['sell']['count'] += 1;
            }
        }

        // Insert into Wallet table
        foreach ($userWalletBalances as $userId => $wallets) {
            foreach ($wallets as $currencyId => $balance) {
                // Calculate average buy and sell exchange rates
                $avgBuyRate = 0;
                $avgSellRate = 0;

                if (isset($userExchangeRates[$userId][$currencyId])) {
                    $buyData = $userExchangeRates[$userId][$currencyId]['buy'];
                    $sellData = $userExchangeRates[$userId][$currencyId]['sell'];

                    if ($buyData['count'] > 0) {
                        $avgBuyRate = $buyData['total'] / $buyData['count'];
                    }
                    if ($sellData['count'] > 0) {
                        $avgSellRate = $sellData['total'] / $sellData['count'];
                    }
                }

                // Insert the data into the Wallet table
                Wallet::create([
                    'user_id' => $userId,
                    'currency_id' => $currencyId,
                    'balance' => $balance,
                    'avgexrate_buy' => $avgBuyRate,
                    'avgexrate_sell' => $avgSellRate,
                ]);
            }
        }

        return redirect()->route('dailystatus.index');
    }
}
