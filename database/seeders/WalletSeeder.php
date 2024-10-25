<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $currencies = Currency::all();

        foreach ($users as $user) {
            // Give each user some random wallets
            $randomCurrencies = $currencies->random(3);

            foreach ($randomCurrencies as $currency) {
                Wallet::create([
                    'user_id' => $user->id,
                    'currency_id' => $currency->id,
                    'balance' => rand(1000, 10000),
                ]);
            }
        }
    }
}