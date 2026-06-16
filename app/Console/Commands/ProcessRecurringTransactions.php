<?php

namespace App\Console\Commands;

use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessRecurringTransactions extends Command
{
    protected $signature = 'transactions:process-recurring';
    protected $description = 'Génère les transactions récurrentes arrivées à échéance';

    public function handle()
    {
        $today = Carbon::today();
        
        $recurringTransactions = RecurringTransaction::with(['account', 'category'])
            ->where('is_active', true)
            ->whereDate('next_date', '<=', $today)
            ->get();

        $count = 0;

        foreach ($recurringTransactions as $recurring) {
            // Créer la transaction
            Transaction::create([
                'account_id' => $recurring->account_id,
                'category_id' => $recurring->category_id,
                'type' => $recurring->type,
                'amount' => $recurring->amount,
                'date' => $recurring->next_date,
                'description' => $recurring->description . ' (Transaction récurrente)',
            ]);

            // Calculer la prochaine date
            $nextDate = Carbon::parse($recurring->next_date);
            
            switch ($recurring->frequency) {
                case 'daily':
                    $nextDate->addDay();
                    break;
                case 'weekly':
                    $nextDate->addWeek();
                    break;
                case 'monthly':
                    $nextDate->addMonth();
                    break;
                case 'yearly':
                    $nextDate->addYear();
                    break;
            }

            $recurring->update(['next_date' => $nextDate]);
            
            $count++;
        }

        $this->info("$count transactions récurrentes ont été générées.");
        
        return Command::SUCCESS;
    }
}
