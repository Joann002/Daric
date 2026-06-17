<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_create_transaction_with_another_users_private_category(): void
    {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $foreignCategory = Category::factory()->expense()->create(); // owned by someone else

        $this->actingAs($user)
            ->post(route('transactions.store'), [
                'account_id' => $account->id,
                'category_id' => $foreignCategory->id,
                'type' => 'expense',
                'amount' => 10,
                'date' => now()->toDateString(),
            ])
            ->assertSessionHasErrors('category_id');

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_can_create_transaction_with_a_global_category(): void
    {
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->create();
        $global = Category::factory()->global()->expense()->create();

        $this->actingAs($user)
            ->post(route('transactions.store'), [
                'account_id' => $account->id,
                'category_id' => $global->id,
                'type' => 'expense',
                'amount' => 10,
                'date' => now()->toDateString(),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('transactions.index'));

        $this->assertDatabaseCount('transactions', 1);
    }
}
