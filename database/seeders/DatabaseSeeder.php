<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Fee;
use App\Models\Settings;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Step 1: Create main currencies first
        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£'],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥'],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$'],
            ['code' => 'THB', 'name' => 'Thailand Baht', 'symbol' => '฿'],
            ['code' => 'NPR', 'name' => 'Nepali Rupee', 'symbol' => '₨'],
            ['code' => 'CNY', 'name' => 'China Yuan', 'symbol' => '¥'],
            ['code' => 'MYR', 'name' => 'Malaysia Ringgit', 'symbol' => 'RM'],
            ['code' => 'KRW', 'name' => 'Korea (South) Won', 'symbol' => '₩'],
            ['code' => 'HKD', 'name' => 'Hong Kong Dollar', 'symbol' => '$'],
        ];
        $settings = [
            ['company_name'=>'WAVE Money Exg', 'address'=>'Bauddha','phone'=>'123456789','email'=>'admin@example.com/','currency_id'=>7,'user_id'=>1],
        ];
        

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
        
        // Step 2: Create exchange rates between all currencies
        $allCurrencies = Currency::all();
        foreach ($allCurrencies as $fromCurrency) {
            foreach ($allCurrencies as $toCurrency) {
                if ($fromCurrency->id !== $toCurrency->id) {
                    ExchangeRate::create([
                        'from_currency_id' => $fromCurrency->id,
                        'to_currency_id' => $toCurrency->id,
                        'rate' => rand(0.5 * 100000, 2 * 100000) / 100000,
                    ]);
                }
            }
        }

        // Step 3: Create admin user
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
        ]);
        foreach ($settings as $setting) {
            Settings::create($setting);
        }

        // Create wallets for admin
        foreach ($allCurrencies as $currency) {
            Wallet::create([
                'user_id' => $admin->id,
                'currency_id' => $currency->id,
                'balance' => rand(1000, 10000),
            ]);
        }

        // Step 4: Create regular users with wallets
        User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) use ($allCurrencies) {
                // Create 3 random wallets for each user
                $userCurrencies = $allCurrencies->random(3);
                
                foreach ($userCurrencies as $currency) {
                    Wallet::create([
                        'user_id' => $user->id,
                        'currency_id' => $currency->id,
                        'balance' => rand(1000, 10000),
                    ]);
                }
            });

        // Step 5: Create transactions for each user
        $users = User::all();
        foreach ($users as $user) {
            $userWallets = $user->wallets;
            
            // Only create transactions if user has at least 2 wallets
            if ($userWallets->count() >= 2) {
                // Create 5 transactions for each user
                for ($i = 0; $i < 5; $i++) {
                    $fromWallet = $userWallets->random();
                    $toWallet = $userWallets->where('id', '!=', $fromWallet->id)->random();
                    
                    // Find exchange rate
                    $exchangeRate = ExchangeRate::where([
                        'from_currency_id' => $fromWallet->currency_id,
                        'to_currency_id' => $toWallet->currency_id,
                    ])->first();

                    if ($exchangeRate) {
                        $fromAmount = rand(100, 1000);
                        $toAmount = $fromAmount * $exchangeRate->rate;

                        // Create transaction
                        $transaction = Transaction::create([
                            'user_id' => $user->id,
                            'from_wallet_id' => $fromWallet->id,
                            'to_wallet_id' => $toWallet->id,
                            'from_amount' => $fromAmount,
                            'to_amount' => $toAmount,
                            'exchange_rate_id' => $exchangeRate->id,
                            'status' => 'Completed',
                        ]);

                        // Create fee for transaction
                        Fee::create([
                            'transaction_id' => $transaction->id,
                            'amount' => $fromAmount * 0.01, // 1% fee
                            'currency_id' => $fromWallet->currency_id,
                            'description' => 'Exchange fee (1%)',
                        ]);
                    }
                }
            }
        }
    }
}