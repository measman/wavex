<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankTrasnsactionRequest;
use App\Http\Resources\BankTransactionResource;
use App\Http\Resources\UserResource;
use App\Models\BankTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use phpDocumentor\Reflection\Types\Resource_;

class BankTransactionController extends Controller
{
    public function index(Request $request)
    {
        
        $banks = BankTransactionResource::collection(
            BankTransaction::with(['banktransactionuser'])
                ->search($request)
                ->paginate(10)
        );
    
        return Inertia::render('Admin/Banktransaction/index', [
            'banks' => $banks,
            'search' => $request->search ?? '',
        ]);
    }
    


    public function create() {
        $users=UserResource::collection(User::all());
        return Inertia::render('Admin/Banktransaction/Create',['users'=>$users]); // Correct spelling
    }

    public function store(BankTrasnsactionRequest $request) {
        BankTransaction::create($request->validated());
        return redirect()->route('banktransactions.index');
    } 
    public function edit(BankTransaction $banktransaction)
    {
        $users = UserResource::collection(User::all());
        return Inertia::render('Admin/Banktransaction/Edit', [
            'banks' => BankTransactionResource::make($banktransaction),
            'users' => $users
        ]);
    }

    public function update(BankTrasnsactionRequest $request, BankTransaction $banktransaction){
        $banktransaction->update($request->validated());
        return redirect()->route('banktransactions.index');
    }
    
    public function destroy($bankid)
    {
        $bankTransaction = BankTransaction::findOrFail($bankid);
        $bankTransaction->delete();
        return redirect()->route('banktransactions.index')->with('success', 'Transaction deleted successfully.');
    }
    
}
