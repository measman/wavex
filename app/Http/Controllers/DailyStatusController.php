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
        foreach ($transactionlogs as $transaction) {
            if (!isset($userWalletBalances[$transaction->user_id])) {
                $userWalletBalances[$transaction->user_id] = [];
            }
            if (!isset($userWalletBalances[$transaction->user_id][$transaction->from_currency_id])) {
                $userWalletBalances[$transaction->user_id][$transaction->from_currency_id] = 0;
            }
            $userWalletBalances[$transaction->user_id][$transaction->from_currency_id] += $transaction->from_amount;
            if (!isset($userWalletBalances[$transaction->user_id][$transaction->to_currency_id])) {
                $userWalletBalances[$transaction->user_id][$transaction->to_currency_id] = 0;
            }
            $userWalletBalances[$transaction->user_id][$transaction->to_currency_id] -= $transaction->to_amount;
        }
        foreach ($userWalletBalances as $userId => $wallets) {
            $id = $userId;
            foreach ($wallets as $walletId => $balance) {
                //dd($balance,$walletId);
                Wallet::create([
                    'user_id' => $id,
                    'currency_id' => $walletId,
                    'balance' => $balance,
                ]);
            }
        }
        return redirect()->route('dailystatus.index');
    }
}
