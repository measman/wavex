<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/User/index');
    }

    public function create()
    {
        $user = UserResource::collection(User::all());
        return Inertia::render('Admin/User/Create', ['user' => $user]);
    }

    public function store(StoreUserRequest $request)
    {
        // User::create($request->validated());
        $data = $request->validated();
        // Ensure isAdmin is properly cast to boolean
        $data['isAdmin'] = (bool) $data['isAdmin'];

        User::create($data);
        return redirect()->route('user.index');
    }
}
