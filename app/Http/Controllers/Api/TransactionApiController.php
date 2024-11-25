<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionApiController extends Controller
{

    protected Transaction $transaction; // Corrected the property name from $excahngerate to $user

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }
    public function transactioninfo(){
        $data = $this->transaction->fetchall();
        return response()->json(['data' => $data]);
    }

    public function transactionedit(Request $request)
    {
        $id = $request->input('id');
        $transaction = Transaction::where('id', $id)->first(); 
        return response()->json($transaction); 
    }
    public function transactionupdate(Request $request){
        $this->transaction->updatetransactioninfo($request);
        return response()->json(['message' => 'Transaction Edited successfully.']);
    }
    
}
