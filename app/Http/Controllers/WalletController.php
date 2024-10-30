<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WalletResource;
use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WalletController extends Controller
{

    public function index(Request $request)
    {
        $wallets = WalletResource::collection(
            Wallet::with(['user', 'currency'])
                ->search($request) 
                ->paginate(10)
        );

        return Inertia::render('Admin/Wallet/index', [
            'wallets' => $wallets,
            'search' => $request->search ?? '',
        ]);
    }


    public function create()
    {
        $currencies = CurrencyResource::collection(Currency::all());
        $users = UserResource::collection(User::all());
        return Inertia::render('Admin/Wallet/Create', ['currencies' => $currencies, 'users' => $users]); // Correct spelling
    }

    public function store(StoreWalletRequest $request)
    {
        Wallet::create($request->validated());
        return redirect()->route('wallets.index');
    }

    public function edit(Wallet $wallet)
    {
        $wallet->load('user', 'currency'); // Load the relationships
        $currencies = CurrencyResource::collection(Currency::all());
        $users = UserResource::collection(User::all());

        return Inertia::render('Admin/Wallet/Edit', [
            'wallet' => WalletResource::make($wallet),
            'currencies' => $currencies,
            'users' => $users
        ]);
    }
    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        $wallet->update($request->validated());
        return redirect()->route('wallets.index');
    }
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();
        return redirect()->route('wallets.index');
    }
}
