<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailyStatusResource;
use App\Models\DailyStatus;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DailyStatusController extends Controller
{
    public function index(){
        $daily_status = DailyStatus::orderBy('date', 'desc')->first();
        return Inertia::render('Admin/Dailystatus/index', ['dailystatus'=>$daily_status]);

    }

    public function insertstatus($id)
    {
        $dailystatus = DailyStatus::findOrFail($id);
        $dailystatus->update([
            'status' => 'day_end',
        ]);
        return redirect()->route('dailystatus.index');
    }
    
}
