<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        $this->updateAccountBalance($transaction, 'add');
    }

    public function updating(Transaction $transaction): void
    {
        // Annuler l'ancien montant avant la mise à jour
        $this->updateAccountBalance($transaction->getOriginal(), 'subtract');
    }

    public function updated(Transaction $transaction): void
    {
        // Appliquer le nouveau montant après la mise à jour
        $this->updateAccountBalance($transaction, 'add');
    }

    public function deleted(Transaction $transaction): void
    {
        $this->updateAccountBalance($transaction, 'subtract');
    }

    private function updateAccountBalance($transaction, $operation): void
    {
        $account = is_array($transaction) 
            ? \App\Models\Account::find($transaction['account_id'])
            : $transaction->account;

        if (!$account) {
            return;
        }

        $amount = is_array($transaction) ? $transaction['amount'] : $transaction->amount;
        $type = is_array($transaction) ? $transaction['type'] : $transaction->type;

        if ($operation === 'add') {
            if ($type === 'income') {
                $account->increment('balance', $amount);
            } else {
                $account->decrement('balance', $amount);
            }
        } else {
            if ($type === 'income') {
                $account->decrement('balance', $amount);
            } else {
                $account->increment('balance', $amount);
            }
        }
    }
}
