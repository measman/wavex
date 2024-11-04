<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Http\Resources\SettingsResource;
use App\Models\Currency;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index(){
        $currencies = CurrencyResource::collection(Currency::all());
        return Inertia::render('Admin/Settings/index',['currencies'=>$currencies]);
    }
}