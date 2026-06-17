<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_spent_amount_only_counts_the_owner_transactions_for_a_shared_category(): void
    {
        $sharedCategory = Category::factory()->global()->expense()->create();

        $owner = User::factory()->create();
        $ownerAccount = Account::factory()->for($owner)->create();
        Transaction::factory()->for($ownerAccount)->for($sharedCategory)->expense()->create([
            'amount' => 100,
            'date' => now()->startOfMonth()->toDateString(),
        ]);

        $other = User::factory()->create();
        $otherAccount = Account::factory()->for($other)->create();
        Transaction::factory()->for($otherAccount)->for($sharedCategory)->expense()->create([
            'amount' => 999,
            'date' => now()->startOfMonth()->toDateString(),
        ]);

        $budget = Budget::factory()->for($owner)->create([
            'category_id' => $sharedCategory->id,
            'month' => now()->format('Y-m'),
            'limit_amount' => 500,
        ]);

        $this->assertEquals(100, $budget->spent_amount);
    }

    public function test_spent_amount_ignores_income_and_other_months(): void
    {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $category = Category::factory()->for($user)->expense()->create();

        // Counts: expense, current month
        Transaction::factory()->for($account)->for($category)->expense()->create([
            'amount' => 50,
            'date' => now()->startOfMonth()->toDateString(),
        ]);
        // Ignored: income in current month
        Transaction::factory()->for($account)->for($category)->income()->create([
            'amount' => 70,
            'date' => now()->startOfMonth()->toDateString(),
        ]);
        // Ignored: expense in a previous month
        Transaction::factory()->for($account)->for($category)->expense()->create([
            'amount' => 30,
            'date' => now()->subMonthNoOverflow()->startOfMonth()->toDateString(),
        ]);

        $budget = Budget::factory()->for($user)->create([
            'category_id' => $category->id,
            'month' => now()->format('Y-m'),
            'limit_amount' => 500,
        ]);

        $this->assertEquals(50, $budget->spent_amount);
    }

    public function test_cannot_create_duplicate_budget_for_same_category_and_month(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->expense()->for($user)->create();

        Budget::factory()->for($user)->create([
            'category_id' => $category->id,
            'month' => '2026-06',
        ]);

        $this->actingAs($user)
            ->post(route('budgets.store'), [
                'category_id' => $category->id,
                'month' => '2026-06',
                'limit_amount' => 200,
            ])
            ->assertSessionHasErrors('category_id');

        $this->assertDatabaseCount('budgets', 1);
    }
}
