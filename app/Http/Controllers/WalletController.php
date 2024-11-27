<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WalletResource;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WalletController extends Controller
{

    public function index()
    {
        $transactions = Wallet::leftJoin('users', 'users.id', '=', 'wallets.user_id')
            ->leftJoin('currencies', 'currencies.id', '=', 'wallets.currency_id')
            ->select('wallets.user_id', 'users.name', 'currencies.code', DB::raw('SUM(wallets.balance) as total_balance'))
            ->groupBy('wallets.user_id', 'users.name', 'currencies.code')
            ->get();
        $authToken = session('auth_token');
        return Inertia::render('Admin/Wallet/index', [
            'token' => $authToken,
        ]);
    }
}
