<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function transactiondelete(Request $request){
        $id = $request->input('id');
        $del = Transaction::find($id);
        $del->delete();
        return response()->json(['message' => 'Transaction deleted successfully.']);
    }

    public function transactionsearch(Request $request){
        $id = $request->input('id');
        $status = $request->input('status');
        $type = $request->input('type');
        $data = $this->transaction->fetchallforsearch($id,$status,$type);
        return response()->json(['data' => $data]);
    }
    
}
