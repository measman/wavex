<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index(){
        $exchangerates=ExchangeRate::all();
        return Inertia::render('Admin/Exchangerate/index', ['exchangerates'=>$exchangerates]);

    }
    public function edit($id) {
        $exchangerates=ExchangeRate::all();
        return Inertia::render('Admin/Exchangerate/Edit', ['id' => $id,'exchangerates'=>$exchangerates]);
    }
    public function add() {
        $currencies=Currency::all();
        return Inertia::render('Admin/Exchangerate/Add',['currencies'=>$currencies]); // Correct spelling
    }
    public function store(Request $request) {
        $validatedData = $request->validate([
            'from_currency_id' => 'required|exists:currencies,id',
            'to_currency_id' => 'required|exists:currencies,id',
            'rate' => 'required|numeric',
        ]);
    
        ExchangeRate::create($validatedData);
        return redirect()->route('admin.exchangerate.index');
    } 
}
