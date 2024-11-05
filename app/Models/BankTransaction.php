<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'bank_from',
        'bank_to',
        'sender',
        'receiver',
        'transaction_method',
        'transaction_amount',
        'remarks',
        'sender_bank_branch',
        'receiver_bank_branch',
        'sender_contact',
        'receiver_contact',
        'created_by',
        
    ];
    protected $with = ['banktransactionuser'];

    public function banktransactionuser()
    {
        return $this->belongsTo(User::class, 'created_by'); // Ensure this is correctly pointing to the User model
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->where(function ($query) use ($request) {
            $query->when($request->search, function ($query) use ($request) {
                $query->where('bank_from', 'like', '%' . $request->search . '%')
                    ->orWhere('bank_to', 'like', '%' . $request->search . '%')
                    ->orWhere('sender', 'like', '%' . $request->search . '%')
                    ->orWhere('receiver', 'like', '%' . $request->search . '%');
            });
        });
    }
}
