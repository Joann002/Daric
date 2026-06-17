<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
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

        // Solde total de tous les comptes
        $totalBalance = $user->accounts()->sum('balance');

        // Transactions du mois en cours
        $monthTransactions = Transaction::whereHas('account', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();

        $monthIncome = $monthTransactions->where('type', 'income')->sum('amount');
        $monthExpense = $monthTransactions->where('type', 'expense')->sum('amount');

        // Comptes avec leurs soldes
        $accounts = $user->accounts()->get();

        // Dernières transactions
        $recentTransactions = Transaction::with(['account', 'category'])
            ->whereHas('account', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
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

        // Évolution des 6 derniers mois (une seule requête, agrégation en mémoire)
        $evolutionStart = Carbon::now()->subMonths(5)->startOfMonth();
        $evolutionEnd = Carbon::now()->endOfMonth();

        $windowTransactions = Transaction::whereHas('account', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereBetween('date', [$evolutionStart->toDateString(), $evolutionEnd->toDateString()])
            ->get(['type', 'amount', 'date']);

        $monthlyEvolution = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthKey = $monthDate->format('Y-m');

            $inMonth = $windowTransactions->filter(
                fn ($t) => Carbon::parse($t->date)->format('Y-m') === $monthKey
            );

            $income = (float) $inMonth->where('type', 'income')->sum('amount');
            $expense = (float) $inMonth->where('type', 'expense')->sum('amount');

            $monthlyEvolution[] = [
                'month' => $monthDate->format('M Y'),
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense,
            ];
        }

        // Budgets du mois avec progression
        $budgets = Budget::with('category')
            ->where('user_id', $user->id)
            ->where('month', $currentMonth)
            ->get()
            ->map(function ($budget) {
                return [
                    'id' => $budget->id,
                    'category' => $budget->category,
                    'limit_amount' => $budget->limit_amount,
                    'spent_amount' => $budget->spent_amount,
                    'remaining' => $budget->limit_amount - $budget->spent_amount,
                    'progress_percentage' => $budget->progress_percentage,
                ];
            });

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
            'currentMonth' => $currentMonth,
        ]);
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
