<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fee;
use App\Models\Transaction;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = Transaction::all();

        foreach ($transactions as $transaction) {
            // Create a 1% fee for each transaction
            Fee::create([
                'transaction_id' => $transaction->id,
                'amount' => $transaction->from_amount * 0.01,
                'currency_id' => $transaction->fromWallet->currency_id,
                'description' => 'Exchange fee (1%)',
            ]);
        }
    }
}