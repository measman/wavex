<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Wallet extends Model
{
    /** @use HasFactory<\Database\Factories\WalletFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'currency_id', 'balance', 'avgexrate_buy', 'avgexrate_sell'];
    // protected $with =['user','currency'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->where(function ($query) use ($request) {
            $query->when($request->search, function ($query) use ($request) {
                // Search by `currency` code
                $query->whereHas('currency', function ($query) use ($request) {
                    $query->where('code', 'like', '%' . $request->search . '%');
                });
            });
        });
    }
    public function fetchall()
    {

        $transactions = Wallet::leftJoin('users', 'users.id', '=', 'wallets.user_id')
            ->leftJoin('currencies', 'currencies.id', '=', 'wallets.currency_id')
            ->select(
                'wallets.user_id',
                'users.name',
                'currencies.code',
                DB::raw('SUM(wallets.balance) as total_balance'),
                DB::raw('ROUND(
                    CASE 
                        WHEN COUNT(wallets.id) = 0 THEN 0
                        ELSE (SUM(wallets.avgexrate_buy) + SUM(wallets.avgexrate_sell)) / (COUNT(wallets.id))
                    END, 2
                ) as avg_exchange_rate')
            )
            ->groupBy('wallets.user_id', 'users.name', 'currencies.code')
            ->get();


        return $transactions;
    }

    public function insertopeningbalance($balanceData)
    {
        $defaultCurrencyId = Settings::where('user_id', Auth::user()->id)->value('currency_id');
        $exchangeRate = ($balanceData['currencyId'] == $defaultCurrencyId) ? 1 : $balanceData['exchange_rate'];
        Wallet::create([
            'user_id' => Auth::user()->id,
            'currency_id' => $balanceData['currencyId'],
            'balance' => $balanceData['balance'],
            'avgexrate_buy' => $exchangeRate,
        ]);
    }
    
}
