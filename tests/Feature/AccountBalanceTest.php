<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Transfer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountBalanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_income_transaction_increases_balance(): void
    {
        $account = Account::factory()->create(['balance' => 0]);
        $category = Category::factory()->income()->create();

        Transaction::factory()->for($account)->for($category)->income()->create(['amount' => 100]);

        $this->assertEquals(100, $account->fresh()->balance);
    }

    public function test_expense_transaction_decreases_balance(): void
    {
        $account = Account::factory()->create(['balance' => 100]);
        $category = Category::factory()->expense()->create();

        Transaction::factory()->for($account)->for($category)->expense()->create(['amount' => 30]);

        $this->assertEquals(70, $account->fresh()->balance);
    }

    public function test_deleting_a_transaction_reverts_the_balance(): void
    {
        $account = Account::factory()->create(['balance' => 0]);
        $category = Category::factory()->income()->create();
        $transaction = Transaction::factory()->for($account)->for($category)->income()->create(['amount' => 100]);

        $transaction->delete();

        $this->assertEquals(0, $account->fresh()->balance);
    }

    public function test_updating_a_transaction_amount_adjusts_the_balance(): void
    {
        $account = Account::factory()->create(['balance' => 0]);
        $category = Category::factory()->income()->create();
        $transaction = Transaction::factory()->for($account)->for($category)->income()->create(['amount' => 100]);

        $transaction->update(['amount' => 150]);

        $this->assertEquals(150, $account->fresh()->balance);
    }

    public function test_transfer_moves_balance_between_accounts(): void
    {
        $from = Account::factory()->create(['balance' => 100]);
        $to = Account::factory()->create(['balance' => 0]);

        Transfer::factory()->create([
            'from_account_id' => $from->id,
            'to_account_id' => $to->id,
            'amount' => 40,
        ]);

        $this->assertEquals(60, $from->fresh()->balance);
        $this->assertEquals(40, $to->fresh()->balance);
    }
}
