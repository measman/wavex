<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fee>
 */
class FeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'amount' => $this->faker->randomFloat(2, 1, 100),
            'currency_id' => Currency::factory(),
            'description' => $this->faker->sentence(),
        ];
    }
}