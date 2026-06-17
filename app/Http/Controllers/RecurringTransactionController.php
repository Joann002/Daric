<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecurringTransactionRequest;
use App\Http\Requests\UpdateRecurringTransactionRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\RecurringTransaction;
use Inertia\Inertia;

class RecurringTransactionController extends Controller
{
    public function index()
    {
        $recurringTransactions = RecurringTransaction::with(['account', 'category'])
            ->whereHas('account', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest('next_date')
            ->get();

        return Inertia::render('RecurringTransactions/Index', [
            'recurringTransactions' => $recurringTransactions,
        ]);
    }

    public function create()
    {
        $accounts = auth()->user()->accounts;
        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->get();

        return Inertia::render('RecurringTransactions/Create', [
            'accounts' => $accounts,
            'categories' => $categories,
        ]);
    }

    public function store(StoreRecurringTransactionRequest $request)
    {
        $account = Account::findOrFail($request->account_id);

        if ($account->user_id !== auth()->id()) {
            abort(403);
        }

        RecurringTransaction::create($request->validated());

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Transaction récurrente créée avec succès');
    }

    public function edit(RecurringTransaction $recurringTransaction)
    {
        $this->authorize('update', $recurringTransaction);

        $accounts = auth()->user()->accounts;
        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->get();

        return Inertia::render('RecurringTransactions/Edit', [
            'recurringTransaction' => $recurringTransaction->load(['account', 'category']),
            'accounts' => $accounts,
            'categories' => $categories,
        ]);
    }

    public function update(UpdateRecurringTransactionRequest $request, RecurringTransaction $recurringTransaction)
    {
        $this->authorize('update', $recurringTransaction);

        $recurringTransaction->update($request->validated());

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Transaction récurrente mise à jour avec succès');
    }

    public function destroy(RecurringTransaction $recurringTransaction)
    {
        $this->authorize('delete', $recurringTransaction);

        $recurringTransaction->delete();

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Transaction récurrente supprimée avec succès');
    }
}
