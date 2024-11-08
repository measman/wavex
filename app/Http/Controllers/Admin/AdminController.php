<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DailyStatusResource;
use App\Models\DailyStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $daily_status = new DailyStatusResource($daily_status);
        return Inertia::render('Admin/Dashboard', ['daily_status' => $daily_status]);
    }
}
