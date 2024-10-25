<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CurrencyController extends Controller
{
    public function index(){
        $currencies = Currency::all();
        return Inertia::render('Admin/Currencies/index', ['currencies'=>$currencies]);
    }
}