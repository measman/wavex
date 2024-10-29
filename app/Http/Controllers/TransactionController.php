<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(){
        $transactions=  TransactionResource::collection(Transaction::paginate(10));
        // dd($transactions);
        // return Inertia::render('Admin/Exchangerate/index', ['exchangerates'=>$exchangerates]);
        return Inertia::render('Admin/Transactions/index', ['transactions'=>$transactions]);

    }
    public function edit(/*ExchangeRate $exchangerate*/)
    {
        // $currencies = CurrencyResource::collection(Currency::all());
        // return Inertia::render('Admin/Exchangerate/Edit', [
        //     'exchangerate' => ExchangeRateResource::make($exchangerate),
        //     'currencies' => $currencies
        // ]);
    }
    
    public function create() {
        // $currencies=CurrencyResource::collection(Currency::all());
        // return Inertia::render('Admin/Exchangerate/Create',['currencies'=>$currencies]); // Correct spelling
    }
    public function store(/*StoreExchangerateRequest $request*/) {
        // ExchangeRate::create($request->validated());
        // return redirect()->route('exchangerate.index');
    } 
    public function update(/*UpdateExchangerateRequest $request, ExchangeRate $exchangerate*/){
        // $exchangerate->update($request->validated());
        // return redirect()->route('exchangerate.index');
    }
    public function destroy(/*ExchangeRate $exchangerate*/){
        // $exchangerate->delete();
        // return redirect()->route('exchangerate.index');
    }
}