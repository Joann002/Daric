<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_edit_another_users_account(): void
    {
        $account = Account::factory()->for(User::factory())->create();
        $intruder = User::factory()->create();

        $this->actingAs($intruder)
            ->get(route('accounts.edit', $account))
            ->assertForbidden();
    }

    public function test_user_cannot_delete_another_users_transaction(): void
    {
        $account = Account::factory()->for(User::factory())->create();
        $category = Category::factory()->create();
        $transaction = Transaction::factory()->for($account)->for($category)->create();
        $intruder = User::factory()->create();

        $this->actingAs($intruder)
            ->delete(route('transactions.destroy', $transaction))
            ->assertForbidden();

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
    }

    public function test_user_cannot_edit_another_users_budget(): void
    {
        $budget = Budget::factory()->for(User::factory())->create();
        $intruder = User::factory()->create();

        $this->actingAs($intruder)
            ->get(route('budgets.edit', $budget))
            ->assertForbidden();
    }

    public function test_global_category_cannot_be_edited(): void
    {
        $user = User::factory()->create();
        $global = Category::factory()->global()->create();

        $this->actingAs($user)
            ->get(route('categories.edit', $global))
            ->assertForbidden();
    }
}
