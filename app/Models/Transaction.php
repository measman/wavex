<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
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
        'type',
        'exchangerate',
        'unit'
    ];

    protected $with = ['user', 'exchangeRate', 'fees'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function fromWallet()
    // {
    //     return $this->belongsTo(Wallet::class, 'from_wallet_id');
    // }

    // public function toWallet()
    // {
    //     return $this->belongsTo(Wallet::class, 'to_wallet_id');
    // }

    public function exchangeRate()
    {
        return $this->belongsTo(ExchangeRate::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function scopeSearch(Builder $query, $search = null)
    {
        return $query->when($search, function ($query) use ($search) {
            // Search by `currency` code in the `fromWallet` relation
            $query->whereHas('fromWallet', function ($query) use ($search) {
                $query->whereHas('currency', function ($query) use ($search) {
                    $query->where('code', 'like', '%' . $search . '%');
                });
            });
        });
    }

    public function getextrainfo() {
        $response = [];
        $response['id'] = $this->select('id')
                               ->orderBy('id', 'desc')
                               ->first()?->id;
        $response['date'] = Carbon::today()->toDateString();
        return $response;
    }
    
    
}
