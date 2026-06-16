<?php

namespace App\Observers;

use App\Models\Transfer;

class TransferObserver
{
    public function created(Transfer $transfer): void
    {
        $this->updateAccountsBalance($transfer, 'add');
    }

    public function updating(Transfer $transfer): void
    {
        $original = $transfer->getOriginal();
        $this->updateAccountsBalance($original, 'subtract');
    }

    public function updated(Transfer $transfer): void
    {
        $this->updateAccountsBalance($transfer, 'add');
    }

    public function deleted(Transfer $transfer): void
    {
        $this->updateAccountsBalance($transfer, 'subtract');
    }

    private function updateAccountsBalance($transfer, $operation): void
    {
        if (is_array($transfer)) {
            $fromAccount = \App\Models\Account::find($transfer['from_account_id']);
            $toAccount = \App\Models\Account::find($transfer['to_account_id']);
            $amount = $transfer['amount'];
        } else {
            $fromAccount = $transfer->fromAccount;
            $toAccount = $transfer->toAccount;
            $amount = $transfer->amount;
        }

        if (!$fromAccount || !$toAccount) {
            return;
        }

        if ($operation === 'add') {
            $fromAccount->decrement('balance', $amount);
            $toAccount->increment('balance', $amount);
        } else {
            $fromAccount->increment('balance', $amount);
            $toAccount->decrement('balance', $amount);
        }
    }
}
