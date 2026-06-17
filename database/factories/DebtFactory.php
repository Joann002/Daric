<?php

namespace Database\Factories;

use App\Models\Debt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Debt>
 */
class DebtFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'person_name' => fake()->name(),
            'amount' => fake()->randomFloat(2, 10, 5000),
            'direction' => fake()->randomElement(['je_dois', 'me_doit']),
            'status' => 'pending',
            'paid_amount' => 0,
            'due_date' => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'description' => fake()->sentence(),
        ];
    }
}
