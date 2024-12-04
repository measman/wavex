<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DailyStatusResource;
use App\Models\Currency;
use App\Models\DailyStatus;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        $daily_status = DailyStatus::orderBy('date', 'desc')->first();
        if (!$daily_status || $daily_status->date != Carbon::today()->toDateString()) {
            $daily_status = DailyStatus::create([
                'date' => Carbon::today()->toDateString(),
                'status' => 'day_start',
            ]);
        }
        $todayDate = Carbon::today()->toDateString();
        $response = Http::get("https://www.nrb.org.np/api/forex/v1/rates", [
            'page' => 1,
            'per_page' => 10,
            'from' => $todayDate,
            'to' => $todayDate,
        ]);
        $responseData = $response->json();
        $todaysexchangerate = $responseData['data']['payload'][0]['rates'];
        $daily_status = new DailyStatusResource($daily_status);
        $id = Auth::user()->id;
        $checkwallet = Wallet::where('user_id', $id)->get();
        $currency=Currency::all();
        $authToken = session('auth_token');
        return Inertia::render('Admin/Dashboard', [
            'daily_status' => $daily_status,
            'todayexchangerate' => $todaysexchangerate,
            'wallet' => $checkwallet,
            'currencies' =>$currency,
            'token'=>$authToken
        ]);
    }
}
