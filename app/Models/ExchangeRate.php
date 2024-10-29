<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ExchangeRate extends Model
{
    /** @use HasFactory<\Database\Factories\ExchangeRateFactory> */
    use HasFactory;

    protected $fillable = ['from_currency_id', 'to_currency_id', 'rate'];

    protected $with = ['fromCurrency', 'toCurrency'];

    public function fromCurrency()
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    public function toCurrency()
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }
    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->where(function ($query) use ($request) {
            $query->when($request->search, function ($query) use ($request) {
                // Search by `fromCurrency` code
                $query->whereHas('fromCurrency', function ($query) use ($request) {
                    $query->where('code', 'like', '%' . $request->search . '%');
                })
                    // Optionally, search by `toCurrency` code as well
                    ->orWhereHas('toCurrency', function ($query) use ($request) {
                        $query->where('code', 'like', '%' . $request->search . '%');
                    });
            });
        });
    }
}
