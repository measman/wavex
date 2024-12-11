<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LocalTransaction;
use Illuminate\Http\Request;

class LocalTransactionApiController extends Controller
{
    protected LocalTransaction $localtransaction; // Corrected the property name from $excahngerate to $user

    public function __construct(LocalTransaction $localtransaction)
    {
        $this->localtransaction = $localtransaction;
    }
    public function localtransactioninfo() {
        $data = $this->localtransaction->fetchall();

        return response()->json(['data' => $data]);
    }
    public function localtransactionedit(Request $request) {
        $id = $request->input('id');
        $localtransaction = $this->localtransaction->getsingleinfo($id);
        if (!$localtransaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        return response()->json($localtransaction);
    }
    
    public function localtransactionupdate(Request $request) {
        $this->localtransaction->updatetransactioninfo($request);
        return response()->json(['message' => 'Transaction Edited successfully.']);
    }    
    public function localtransactiondelete(Request $request) {
        $id = $request->input('id');
        $localtransaction = $this->localtransaction->getsingleinfo($id);
        $localtransaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully.']);
    }   
}
