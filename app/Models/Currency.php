<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /** @use HasFactory<\Database\Factories\CurrencyFactory> */
    use HasFactory;

    protected $fillable = ['code', 'name', 'symbol'];

    protected $with = ['wallets'];

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    
}