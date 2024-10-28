<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request){
        $user = UserResource::collection(User::all());
        return Inertia::render('Admin/User/index', ['user'=>$user]);
    }
}