<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    /** @use HasFactory<\Database\Factories\FeeFactory> */
    use HasFactory;

    protected $fillable = ['transaction_id', 'amount', 'currency_id', 'description'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}