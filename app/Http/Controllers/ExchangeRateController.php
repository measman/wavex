<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index(){
        $exchangerates=ExchangeRate::all();
        return Inertia::render('Admin/Exchangerate/index', ['exchangerates'=>$exchangerates]);

    }
}
