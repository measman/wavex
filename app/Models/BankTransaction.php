<?php

namespace App\Models;

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
        'created_by'
    ];
}
