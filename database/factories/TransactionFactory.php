<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\ExchangeRate;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Transaction::class;

    public function definition()
    {
        $fromAmount = $this->faker->randomFloat(2, 10, 1000);
        $rate = $this->faker->randomFloat(4, 0.5, 2);
        
        return [
            'user_id' => User::factory(),
            'from_wallet_id' => Wallet::factory(),
            'to_wallet_id' => Wallet::factory(),
            'from_amount' => $fromAmount,
            'to_amount' => $fromAmount * $rate,
            'exchange_rate_id' => ExchangeRate::factory(),
            'status' => $this->faker->randomElement(['Pending', 'Completed', 'Failed']),
        ];
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Completed',
            ];
        });
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Pending',
            ];
        });
    }

    public function failed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Failed',
            ];
        });
    }
}