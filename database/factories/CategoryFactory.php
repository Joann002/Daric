<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->word(),
            'type' => fake()->randomElement(['income', 'expense']),
            'color' => fake()->hexColor(),
            'icon' => null,
        ];
    }

    /**
     * A global category shared by all users (user_id null).
     */
    public function global(): static
    {
        return $this->state(fn () => ['user_id' => null]);
    }

    public function expense(): static
    {
        return $this->state(fn () => ['type' => 'expense']);
    }

    public function income(): static
    {
        return $this->state(fn () => ['type' => 'income']);
    }
}
