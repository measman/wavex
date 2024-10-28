<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Http\Resources\ExchangeRateResource;
use App\Models\ExchangeRate;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index(){
        $exchangerates=  ExchangeRateResource::collection(ExchangeRate::with(['fromCurrency','toCurrency'])->paginate(10));
        return Inertia::render('Admin/Exchangerate/index', ['exchangerates'=>$exchangerates]);

    }
    public function edit(ExchangeRate $id) {
        // $currencies=CurrencyResource::collection(Currency::all());
        return Inertia::render('Admin/Exchangerate/Edit',['test'=>'test','exchangerate'=>ExchangeRateResource::make($id)]);
    }
    public function create() {
        $currencies=CurrencyResource::collection(Currency::all());
        return Inertia::render('Admin/Exchangerate/Create',['currencies'=>$currencies]); // Correct spelling
    }
    public function store(Request $request) {
        $validatedData = $request->validate([
            'from_currency_id' => 'required|exists:currencies,id',
            'to_currency_id' => 'required|exists:currencies,id',
            'rate' => 'required|numeric',
        ]);
    
        ExchangeRate::create($validatedData);
        return redirect()->route('exchangerate.index');
    } 
}
