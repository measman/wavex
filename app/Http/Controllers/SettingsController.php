<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Http\Resources\SettingsResource;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index(Request $request){

        $authuser = Auth::user();
        $currencies = CurrencyResource::collection(Currency::all());
        return Inertia::render('Admin/Settings/index',['currencies'=>$currencies, 'settings'=>$authuser->settings]);
    }
}