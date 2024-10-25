<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ExchangeRate;
use App\Models\Transaction;
class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $wallets = $user->wallets;

            if ($wallets->count() >= 2) {
                for ($i = 0; $i < 3; $i++) {
                    // Create random transactions between user's wallets
                    $fromWallet = $wallets->random();
                    $toWallet = $wallets->where('id', '!=', $fromWallet->id)->random();

                    $exchangeRate = ExchangeRate::where([
                        'from_currency_id' => $fromWallet->currency_id,
                        'to_currency_id' => $toWallet->currency_id,
                    ])->first();

                    if ($exchangeRate) {
                        $fromAmount = rand(100, 1000);
                        $toAmount = $fromAmount * $exchangeRate->rate;

                        Transaction::create([
                            'user_id' => $user->id,
                            'from_wallet_id' => $fromWallet->id,
                            'to_wallet_id' => $toWallet->id,
                            'from_amount' => $fromAmount,
                            'to_amount' => $toAmount,
                            'exchange_rate_id' => $exchangeRate->id,
                            'status' => 'Completed',
                        ]);
                    }
                }
            }
        }
    }
}