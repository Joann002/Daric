<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['account', 'category'])
            ->whereHas('account', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest('date');

        // Filtres
        if ($request->account_id) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->search) {
            $query->where('description', 'like', '%'.$request->search.'%');
        }

        $transactions = $query->paginate(20)->withQueryString();

        $accounts = auth()->user()->accounts;
        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->get();

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
            'accounts' => $accounts,
            'categories' => $categories,
            'filters' => $request->only(['account_id', 'category_id', 'type', 'start_date', 'end_date', 'search']),
        ]);
    }

    public function create(Request $request)
    {
        $accounts = auth()->user()->accounts;
        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->get();

        $defaultDate = null;
        if ($request->filled('date') && strtotime($request->date) !== false) {
            $defaultDate = date('Y-m-d', strtotime($request->date));
        }

        return Inertia::render('Transactions/Create', [
            'accounts' => $accounts,
            'categories' => $categories,
            'defaultDate' => $defaultDate,
        ]);
    }

    public function store(StoreTransactionRequest $request)
    {
        $account = Account::findOrFail($request->account_id);

        if ($account->user_id !== auth()->id()) {
            abort(403);
        }

        Transaction::create($request->validated());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction créée avec succès');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->load(['account', 'category']);

        return Inertia::render('Transactions/Show', [
            'transaction' => $transaction,
        ]);
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $accounts = auth()->user()->accounts;
        $categories = Category::whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->get();

        return Inertia::render('Transactions/Edit', [
            'transaction' => $transaction->load(['account', 'category']),
            'accounts' => $accounts,
            'categories' => $categories,
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $account = Account::findOrFail($request->account_id);

        if ($account->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->update($request->validated());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction mise à jour avec succès');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction supprimée avec succès');
    }
}
