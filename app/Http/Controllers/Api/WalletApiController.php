<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletApiController extends Controller
{
    protected Wallet $wallet; // Corrected the property name from $excahngerate to $user

    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }
    public function walletinfo(){
        $data = $this->wallet->fetchall();
        return response()->json(['data' => $data]);
    }
}
