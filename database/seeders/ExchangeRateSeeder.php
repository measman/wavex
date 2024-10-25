<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\ExchangeRate;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseRates = [
            'EUR' => 0.85,
            'GBP' => 0.73,
            'JPY' => 110.0,
            'AUD' => 1.35,
            'CAD' => 1.25,
            'CHF' => 0.92,
            'CNY' => 6.45,
        ];

        $usd = Currency::where('code', 'USD')->first();

        foreach ($baseRates as $code => $rate) {
            $toCurrency = Currency::where('code', $code)->first();

            // USD to other currency
            ExchangeRate::create([
                'from_currency_id' => $usd->id,
                'to_currency_id' => $toCurrency->id,
                'rate' => $rate,
            ]);

            // Other currency to USD
            ExchangeRate::create([
                'from_currency_id' => $toCurrency->id,
                'to_currency_id' => $usd->id,
                'rate' => 1 / $rate,
            ]);
        }
    }
}