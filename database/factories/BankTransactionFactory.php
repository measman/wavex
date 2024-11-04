<?php

namespace Database\Factories;

use App\Models\BankTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankTransactionFactory extends Factory
{
    protected $model = BankTransaction::class;

    public function definition()
    {
        return [
            'transaction_date' => $this->faker->date(), // Generates a random date
            'bank_from' => $this->faker->company(), // Random bank name
            'bank_to' => $this->faker->company(), // Random bank name
            'sender' => $this->faker->name(), // Random sender name
            'receiver' => $this->faker->name(), // Random receiver name
            'transaction_method' => $this->faker->word(), // Random transaction method
            'transaction_amount' => $this->faker->randomFloat(2, 10, 1000), // Random amount between 10 and 1000
            'remarks' => $this->faker->sentence(), // Random remarks
            'sender_bank_branch' => $this->faker->word(), // Random sender bank branch
            'receiver_bank_branch' => $this->faker->word(), // Random receiver bank branch
            'sender_contact' => $this->faker->phoneNumber(), // Random sender contact number
            'receiver_contact' => $this->faker->phoneNumber(), // Random receiver contact number
            'created_by' => User::factory(), // Assumes you have a User factory to create a user
        ];
    }
}
