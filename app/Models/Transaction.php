<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_wallet_id',
        'to_wallet_id',
        'from_amount',
        'to_amount',
        'exchange_rate_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromWallet()
    {
        return $this->belongsTo(Wallet::class, 'from_wallet_id');
    }

    public function toWallet()
    {
        return $this->belongsTo(Wallet::class, 'to_wallet_id');
    }

    public function exchangeRate()
    {
        return $this->belongsTo(ExchangeRate::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }
}