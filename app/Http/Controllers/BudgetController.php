<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use App\Models\Category;
use Carbon\Carbon;
use Inertia\Inertia;

class BudgetController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        $budgets = Budget::with('category')
            ->where('user_id', auth()->id())
            ->where('month', $currentMonth)
            ->get()
            ->map(function ($budget) {
                return [
                    'id' => $budget->id,
                    'category' => $budget->category,
                    'month' => $budget->month,
                    'limit_amount' => $budget->limit_amount,
                    'spent_amount' => $budget->spent_amount,
                    'remaining' => $budget->limit_amount - $budget->spent_amount,
                    'progress_percentage' => $budget->progress_percentage,
                ];
            });

        $expenseCategories = Category::where('type', 'expense')
            ->where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', auth()->id());
            })
            ->get();

        return Inertia::render('Budgets/Index', [
            'budgets' => $budgets,
            'expenseCategories' => $expenseCategories,
            'currentMonth' => $currentMonth,
        ]);
    }

    public function create()
    {
        $expenseCategories = Category::where('type', 'expense')
            ->where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', auth()->id());
            })
            ->get();

        return Inertia::render('Budgets/Create', [
            'expenseCategories' => $expenseCategories,
        ]);
    }

    public function store(StoreBudgetRequest $request)
    {
        auth()->user()->budgets()->create($request->validated());

        return redirect()->route('budgets.index')
            ->with('success', 'Budget créé avec succès');
    }

    public function edit(Budget $budget)
    {
        $this->authorize('update', $budget);

        $expenseCategories = Category::where('type', 'expense')
            ->where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', auth()->id());
            })
            ->get();

        return Inertia::render('Budgets/Edit', [
            'budget' => $budget->load('category'),
            'expenseCategories' => $expenseCategories,
        ]);
    }

    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        $this->authorize('update', $budget);

        $budget->update($request->validated());

        return redirect()->route('budgets.index')
            ->with('success', 'Budget mis à jour avec succès');
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);

        $budget->delete();

        return redirect()->route('budgets.index')
            ->with('success', 'Budget supprimé avec succès');
    }
}
