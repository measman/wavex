<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Http\Resources\ExchangeRateResource;
use App\Http\Resources\TransactionResource;
use App\Models\Currency;
use App\Models\ExchangeRate;
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
        $excahngerates=ExchangeRateResource::collection(ExchangeRate::all());
        return Inertia::render('Admin/Transactions/Create', ['transactions' => $transactions,'excahngerates'=>$excahngerates]); // Correct spelling
    }

    public function store(Request $request) {
        $user_id=Auth::user()->id;
        $exchangerate_id=$request->exrate['id'];
        $from_curency_id = $request->exrate['from_currency_id'];
        $to_curency_id = $request->exrate['to_currency_id'];
        $rate = $request->exrate['rate'];
        $type=$request->type;
        $amount_from=$request->amount_from;
        $amount_to=$amount_from * $rate ;
        $status=$request->status;
        $from_wallet_id = Wallet::where('currency_id', $from_curency_id)
            ->where('user_id', $user_id)
            ->pluck('id')
            ->first();
        $to_wallet_id = Wallet::where('currency_id', $to_curency_id)
            ->where('user_id', $user_id)
            ->pluck('id')
            ->first();
        
        //dd($request);
        //dd($user_id,$from_wallet_id,$to_wallet_id,$amount_from,$amount_to,$exchangerate_id,$status,$type);
        //Transaction::create($request->validated());
        Transaction::create([
            'user_id' => $user_id,
            'from_wallet_id' => $from_wallet_id,
            'to_wallet_id' => $to_wallet_id,
            'from_amount' => $amount_from,
            'to_amount' => $amount_to,
            'exchange_rate_id' => $exchangerate_id,
            'status' => $status,
            'type' => $type,
        ]);
        return redirect()->route('transactions.index');
    } 

    // public function edit(/*ExchangeRate $exchangerate*/)
    // {
    //     // $currencies = CurrencyResource::collection(Currency::all());
    //     // return Inertia::render('Admin/Exchangerate/Edit', [
    //     //     'exchangerate' => ExchangeRateResource::make($exchangerate),
    //     //     'currencies' => $currencies
    //     // ]);
    // }

    // public function buy()
    // {
    //     $currencies = CurrencyResource::collection(Currency::all());
    //     return Inertia::render('Admin/Transactions/Buy', ['currencies' => $currencies]); // Correct spelling
    // }
    // public function sell()
    // {
    //     // $currencies=CurrencyResource::collection(Currency::all());
    //     // return Inertia::render('Admin/Exchangerate/Create',['currencies'=>$currencies]); // Correct spelling
    // }
    // public function store(/*StoreExchangerateRequest $request*/)
    // {
    //     // ExchangeRate::create($request->validated());
    //     // return redirect()->route('exchangerate.index');
    // }
    // public function update(/*UpdateExchangerateRequest $request, ExchangeRate $exchangerate*/)
    // {
    //     // $exchangerate->update($request->validated());
    //     // return redirect()->route('exchangerate.index');
    // }
    // public function destroy(/*ExchangeRate $exchangerate*/)
    // {
    //     // $exchangerate->delete();
    //     // return redirect()->route('exchangerate.index');
    // }
}
