<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExchangerateRequest;
use App\Http\Requests\UpdateExchangerateRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Http\Resources\ExchangeRateResource;
use App\Models\ExchangeRate;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index(Request $request){
        $exchangerateQuery = ExchangeRate::search($request);
        $exchangerates=  ExchangeRateResource::collection($exchangerateQuery->paginate(10));
        return Inertia::render('Admin/Exchangerate/index', ['exchangerates'=>$exchangerates,'search' => $request->search ?? '',]);

    }
    public function edit(ExchangeRate $exchangerate)
    {
        $currencies = CurrencyResource::collection(Currency::all());
        return Inertia::render('Admin/Exchangerate/Edit', [
            'exchangerate' => ExchangeRateResource::make($exchangerate),
            'currencies' => $currencies
        ]);
    }
    
    public function create() {
        $currencies=CurrencyResource::collection(Currency::all());
        return Inertia::render('Admin/Exchangerate/Create',['currencies'=>$currencies]); // Correct spelling
    }
    public function store(StoreExchangerateRequest $request) {
        ExchangeRate::create($request->validated());
        return redirect()->route('exchangerate.index');
    } 
    public function update(UpdateExchangerateRequest $request, ExchangeRate $exchangerate){
        $exchangerate->update($request->validated());
        return redirect()->route('exchangerate.index');
    }
    public function destroy(ExchangeRate $exchangerate){
        $exchangerate->delete();
        return redirect()->route('exchangerate.index');
    }
}
