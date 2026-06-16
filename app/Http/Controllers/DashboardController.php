<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $currentMonth = $request->month ?? Carbon::now()->format('Y-m');
        
        // Solde total de tous les comptes
        $totalBalance = $user->accounts()->sum('balance');
        
        // Transactions du mois en cours
        $monthTransactions = Transaction::whereHas('account', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
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
            ->whereRaw("DATE_FORMAT(transactions.date, '%Y-%m') = ?", [$currentMonth])
            ->groupBy('categories.id', 'categories.name', 'categories.color', 'categories.icon')
            ->orderByDesc('total')
            ->get();
        
        // Revenus par catégorie pour le mois
        $incomesByCategory = Transaction::select('categories.name', 'categories.color', 'categories.icon', DB::raw('SUM(transactions.amount) as total'))
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->where('accounts.user_id', $user->id)
            ->where('transactions.type', 'income')
            ->whereRaw("DATE_FORMAT(transactions.date, '%Y-%m') = ?", [$currentMonth])
            ->groupBy('categories.id', 'categories.name', 'categories.color', 'categories.icon')
            ->orderByDesc('total')
            ->get();
        
        // Évolution des 6 derniers mois
        $monthlyEvolution = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            
            $income = Transaction::whereHas('account', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('type', 'income')
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
            ->sum('amount');
            
            $expense = Transaction::whereHas('account', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('type', 'expense')
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
            ->sum('amount');
            
            $monthlyEvolution[] = [
                'month' => Carbon::createFromFormat('Y-m', $month)->format('M Y'),
                'income' => (float) $income,
                'expense' => (float) $expense,
                'balance' => (float) ($income - $expense),
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
}
