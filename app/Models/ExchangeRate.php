<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function getFirstValueOf(int $from_curency_id, int $to_curency_id)
    {
        return $this->where('from_currency_id', $from_curency_id)
            ->where('to_currency_id', $to_curency_id)
            ->select('rate', 'id')
            ->first();
    }

    public function storebuytransaction(
        int $user_id,
        int $from_curency_id,
        int $to_curency_id,
        float $amount_from,
        float $amount_to,
        int $exchange_rate_id,
        string $type,
        float $exchange_rate,
        int $unit
    ) {
        Transaction::create([
            'user_id' => $user_id,
            'from_wallet_id' => $from_curency_id,
            'to_wallet_id' => $to_curency_id,
            'from_amount' => $amount_from,
            'to_amount' => $amount_to,
            'exchange_rate_id' => $exchange_rate_id,
            'status' => 'Completed',
            'type' => $type,
            'exchangerate' => $exchange_rate,
            'unit' => $unit

        ]);
    }

    public function storeselltransaction(
        int $user_id,
        int $from_curency_id,
        int $to_curency_id,
        float $amount_from,
        float $amount_to,
        int $exchange_rate_id,
        string $type,
        float $exchange_rate,
        int $unit
    ) {
        Transaction::create([
                'user_id' => $user_id,
                'from_wallet_id' => $from_curency_id,
                'to_wallet_id' => $to_curency_id,
                'from_amount' => $amount_to,
                'to_amount' => $amount_from,
                'exchange_rate_id' => $exchange_rate_id,
                'status' => 'Completed',
                'type' => $type,
                'exchangerate' => $exchange_rate,
                'unit' => $unit
            ]);
    }
    public function getliveexchangerate(){
        $todayDate = Carbon::today()->toDateString();
        $response = Http::get("https://www.nrb.org.np/api/forex/v1/rates", [
            'page' => 1,
            'per_page' => 10,
            'from' => $todayDate,
            'to' => $todayDate,
        ]);
        $responseData = $response->json();
        $todaysexchangerate = $responseData['data']['payload'][0]['rates'];
        return $todaysexchangerate;
    }
}
