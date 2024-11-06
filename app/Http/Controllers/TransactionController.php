<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Http\Resources\ExchangeRateResource;
use App\Http\Resources\TransactionResource;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\Settings;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $transactions = TransactionResource::collection(
            Transaction::where('user_id', $user_id)->paginate(10)
        );

        //dd($//transactions);
        // return Inertia::render('Admin/Exchangerate/index', ['exchangerates'=>$exchangerates]);
        return Inertia::render('Admin/Transactions/index', ['transactions' => $transactions]);
    }

    public function create()
    {
        $user_id = Auth::user()->id;
        $transactions = TransactionResource::collection(
            Transaction::where('user_id', $user_id)->get()
        );
        $currencies=CurrencyResource::collection((Currency::all()));
        $excahngerates = ExchangeRateResource::collection(ExchangeRate::all());
        return Inertia::render('Admin/Transactions/Create', ['transactions' => $transactions, 'excahngerates' => $excahngerates,'currencies'=>$currencies]); // Correct spelling
    }

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $currency_id = $request->excurrency['id'];
        $type = $request->type;
        $amount_from = $request->amount_from;
        $basecurrency = Settings::first();
        $basecurrency_id=$basecurrency->currency_id;
        $status = $request->status;
        if ($type == 'buy') {
            $from_curency_id = $currency_id;
            $to_curency_id = $basecurrency_id;

            $exchangerate=ExchangeRate::where('from_currency_id',$from_curency_id)
                                    ->where('to_currency_id',$to_curency_id)
                                    ->pluck('rate')
                                    ->first();
            $amount_to=$amount_from * $exchangerate;
            $exchange_rate_id=ExchangeRate::where('from_currency_id',$from_curency_id)
            ->where('to_currency_id',$to_curency_id)
            ->pluck('id')
            ->first();
                            
        }else {
            $from_curency_id = $basecurrency_id;
            $to_curency_id =$currency_id;
            $exchangerate=ExchangeRate::where('from_currency_id',$from_curency_id)
            ->where('to_currency_id',$to_curency_id)
            ->pluck('rate')
            ->first();
            $amount_to=$amount_from * $exchangerate;
            $exchange_rate_id=ExchangeRate::where('from_currency_id',$from_curency_id)
            ->where('to_currency_id',$to_curency_id)
            ->pluck('id')
            ->first();
        }
        
        $from_wallet_id = Wallet::where('currency_id', $from_curency_id)
            ->where('user_id', $user_id)
            ->pluck('id')
            ->first();
        $to_wallet_id = Wallet::where('currency_id', $to_curency_id)
            ->where('user_id', $user_id)
            ->pluck('id')
            ->first();

        Transaction::create([
            'user_id' => $user_id,
            'from_wallet_id' => $from_wallet_id,
            'to_wallet_id' => $to_wallet_id,
            'from_amount' => $amount_from,
            'to_amount' => $amount_to,
            'exchange_rate_id' => $exchange_rate_id,
            'status' => $status,
            'type' => $type,
        ]);
        $from_wallet_balance = Wallet::where('id', $from_wallet_id)
            ->where('user_id', $user_id)
            ->pluck('balance')
            ->first();
        $to_wallet_balance = Wallet::where('id', $to_wallet_id)
            ->where('user_id', $user_id)
            ->pluck('balance')
            ->first();
        $new_from_wallet_balance = $from_wallet_balance + $amount_from;
        $new_to_wallet_balance = $to_wallet_balance - $amount_to;

        Wallet::where('id', $from_wallet_id)
            ->where('user_id', $user_id)
            ->update(['balance' => $new_from_wallet_balance]);

        Wallet::where('id', $to_wallet_id)
            ->where('user_id', $user_id)
            ->update(['balance' => $new_to_wallet_balance]);
        return redirect()->route('transactions.index');
    }
}
