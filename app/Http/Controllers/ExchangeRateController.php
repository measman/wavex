<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExchangeRateResource;
use App\Models\ExchangeRate;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index(){
        $exchangerates=  ExchangeRateResource::collection(ExchangeRate::with(['fromCurrency','toCurrency'])->get());
        return Inertia::render('Admin/Exchangerate/index', ['exchangerates'=>$exchangerates]);

    }
}