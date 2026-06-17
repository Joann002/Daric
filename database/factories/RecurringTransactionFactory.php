<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Category;
use App\Models\RecurringTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecurringTransaction>
 */
class RecurringTransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'category_id' => Category::factory(),
            'type' => fake()->randomElement(['income', 'expense']),
            'amount' => fake()->randomFloat(2, 1, 1000),
            'frequency' => fake()->randomElement(['daily', 'weekly', 'monthly', 'yearly']),
            'next_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
