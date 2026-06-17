<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\Transfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $currentMonth = $request->month ?? Carbon::now()->format('Y-m');
        [$monthStart, $monthEnd] = $this->monthRange($currentMonth);

        // 6-month window shared by the evolution chart and per-account sparklines
        $windowStart = Carbon::now()->subMonths(5)->startOfMonth();
        $windowEnd = Carbon::now()->endOfMonth();
        $monthKeys = collect(range(5, 0))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        $windowTransactions = Transaction::whereHas('account', fn ($q) => $q->where('user_id', $user->id))
            ->whereBetween('date', [$windowStart->toDateString(), $windowEnd->toDateString()])
            ->get(['account_id', 'type', 'amount', 'date']);

        $windowTransfers = Transfer::where(function ($q) use ($user) {
            $q->whereHas('fromAccount', fn ($a) => $a->where('user_id', $user->id))
                ->orWhereHas('toAccount', fn ($a) => $a->where('user_id', $user->id));
        })
            ->whereBetween('date', [$windowStart->toDateString(), $windowEnd->toDateString()])
            ->get(['from_account_id', 'to_account_id', 'amount', 'date']);

        // Solde total de tous les comptes
        $totalBalance = $user->accounts()->sum('balance');

        // Transactions du mois en cours
        $monthTransactions = Transaction::whereHas('account', fn ($q) => $q->where('user_id', $user->id))
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();

        $monthIncome = $monthTransactions->where('type', 'income')->sum('amount');
        $monthExpense = $monthTransactions->where('type', 'expense')->sum('amount');

        // Comptes + mini-historique de solde (sparkline)
        $accounts = $user->accounts()->get()->map(function ($account) use ($windowTransactions, $windowTransfers, $monthKeys) {
            $account->sparkline = $this->accountBalanceSeries(
                $account,
                $windowTransactions,
                $windowTransfers,
                $monthKeys,
            );

            return $account;
        });

        // Dernières transactions
        $recentTransactions = Transaction::with(['account', 'category'])
            ->whereHas('account', fn ($q) => $q->where('user_id', $user->id))
            ->latest('date')
            ->take(10)
            ->get();

        // Dépenses par catégorie pour le mois
        $expensesByCategory = Transaction::select('categories.name', 'categories.color', 'categories.icon', DB::raw('SUM(transactions.amount) as total'))
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->where('accounts.user_id', $user->id)
            ->where('transactions.type', 'expense')
            ->whereBetween('transactions.date', [$monthStart, $monthEnd])
            ->groupBy('categories.id', 'categories.name', 'categories.color', 'categories.icon')
            ->orderByDesc('total')
            ->get();

        // Revenus par catégorie pour le mois
        $incomesByCategory = Transaction::select('categories.name', 'categories.color', 'categories.icon', DB::raw('SUM(transactions.amount) as total'))
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->where('accounts.user_id', $user->id)
            ->where('transactions.type', 'income')
            ->whereBetween('transactions.date', [$monthStart, $monthEnd])
            ->groupBy('categories.id', 'categories.name', 'categories.color', 'categories.icon')
            ->orderByDesc('total')
            ->get();

        // Évolution des 6 derniers mois (agrégation en mémoire)
        $monthlyEvolution = $monthKeys->map(function ($monthKey) use ($windowTransactions) {
            $inMonth = $windowTransactions->filter(
                fn ($t) => Carbon::parse($t->date)->format('Y-m') === $monthKey,
            );

            $income = (float) $inMonth->where('type', 'income')->sum('amount');
            $expense = (float) $inMonth->where('type', 'expense')->sum('amount');

            return [
                'month' => Carbon::createFromFormat('Y-m', $monthKey)->format('M Y'),
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense,
            ];
        });

        // Budgets du mois avec progression
        $budgets = Budget::with('category')
            ->where('user_id', $user->id)
            ->where('month', $currentMonth)
            ->get()
            ->map(fn ($budget) => [
                'id' => $budget->id,
                'category' => $budget->category,
                'limit_amount' => $budget->limit_amount,
                'spent_amount' => $budget->spent_amount,
                'remaining' => $budget->limit_amount - $budget->spent_amount,
                'progress_percentage' => $budget->progress_percentage,
            ]);

        // Évènements du calendrier (transactions du mois sélectionné)
        $calendarEvents = Transaction::with('category:id,name,color')
            ->whereHas('account', fn ($q) => $q->where('user_id', $user->id))
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'title' => $t->category->name ?? 'Transaction',
                'start' => Carbon::parse($t->date)->toDateString(),
                'type' => $t->type,
                'amount' => (float) $t->amount,
                'color' => $t->category->color ?? null,
            ]);

        return Inertia::render('Dashboard', [
            'totalBalance' => $totalBalance,
            'monthIncome' => $monthIncome,
            'monthExpense' => $monthExpense,
            'accounts' => $accounts,
            'recentTransactions' => $recentTransactions,
            'expensesByCategory' => $expensesByCategory,
            'incomesByCategory' => $incomesByCategory,
            'monthlyEvolution' => $monthlyEvolution,
            'budgets' => $budgets,
            'calendarEvents' => $calendarEvents,
            'currentMonth' => $currentMonth,
        ]);
    }

    /**
     * End-of-month balance series (6 points) for an account, reconstructed
     * backward from its current balance using transactions and transfers.
     *
     * @return array<int, float>
     */
    private function accountBalanceSeries($account, $transactions, $transfers, $monthKeys): array
    {
        $delta = array_fill_keys($monthKeys->all(), 0.0);

        foreach ($transactions as $t) {
            if ($t->account_id != $account->id) {
                continue;
            }
            $key = Carbon::parse($t->date)->format('Y-m');
            if (! array_key_exists($key, $delta)) {
                continue;
            }
            $delta[$key] += $t->type === 'income' ? (float) $t->amount : -(float) $t->amount;
        }

        foreach ($transfers as $tr) {
            $key = Carbon::parse($tr->date)->format('Y-m');
            if (! array_key_exists($key, $delta)) {
                continue;
            }
            if ($tr->from_account_id == $account->id) {
                $delta[$key] -= (float) $tr->amount;
            }
            if ($tr->to_account_id == $account->id) {
                $delta[$key] += (float) $tr->amount;
            }
        }

        $keys = $monthKeys->all();
        $balances = array_fill(0, count($keys), 0.0);
        $running = (float) $account->balance;

        for ($i = count($keys) - 1; $i >= 0; $i--) {
            $balances[$i] = round($running, 2);
            $running -= $delta[$keys[$i]];
        }

        return $balances;
    }

    /**
     * Return the [start, end] date strings for a "YYYY-MM" month (DB-portable).
     *
     * @return array{0: string, 1: string}
     */
    private function monthRange(string $month): array
    {
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        return [$start->toDateString(), $start->copy()->endOfMonth()->toDateString()];
    }
}
