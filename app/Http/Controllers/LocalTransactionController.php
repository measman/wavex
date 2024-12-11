<?php

namespace App\Http\Controllers;

use App\Models\LocalTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocalTransactionController extends Controller
{
    protected LocalTransaction $localtransaction; // Corrected the property name from $excahngerate to $user

    public function __construct(LocalTransaction $localtransaction)
    {
        $this->localtransaction = $localtransaction;
    }
    public function index(Request $request)
    {
        $authToken = session('auth_token');
        return Inertia::render('Admin/LocalTransaction/index', [
            'token'=>$authToken
        ]);
    }
    public function create(){
        return Inertia::render('Admin/LocalTransaction/Create');
    }
    public function store(Request $request){
        $request->validate([
            'amount' => 'required',
            'type' => 'required',
        ]);
        $this->localtransaction->createlocaltransaction($request);
        return redirect()->route('localtransactions.index');
    }
}
