<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AdminAuthController extends Controller
{
    // public function showLoginForm()
    // {
    //     return Inertia::render('Admin/Auth/Login');
    // }

    // public function login(Request $request)
    // {
    //     // Add your login logic here
    //     // // Check if the user is an admin and redirect accordingly
    //     // if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'isAdmin' => true])) {

    //     //     $user = User::where('email', $request->email)->first();

    //     //     $token = $user->createToken($user->name);
    //     //     // dd($token);
    //     //     Log::info("adsfsa");

    //     //     return redirect()->route('admin.dashboard'); // Redirect to the admin dashboard
    //     // }

    //     // return redirect()->route('admin.login')->with('error', 'Invalid credentials.');
    // }
    

    // public function logout(Request $request)
    // {        
    //     Auth::guard('web')->logout();
       
    //     $request->session()->invalidate();

    //     $request->session()->regenerateToken();

    //     // return redirect('/');
    //     return redirect()->route('admin.login');
    // }
}