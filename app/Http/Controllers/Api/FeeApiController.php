<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class FeeApiController extends Controller
{
    public function __invoke()
    {
        $test = [
            [
                'id' => "1",
                'name' => "apple",
                'email' => "susahn@gmail.com",
                'age' => "15",
                'country' => "nepal",
            ]
        ];

        $test2=Currency::all();
        return response()->json(['data' => $test2]);
    }
    
}
