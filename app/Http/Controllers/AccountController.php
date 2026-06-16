<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = auth()->user()->accounts()
            ->withSum('transactions as total_transactions', 'amount')
            ->get();

        return Inertia::render('Accounts/Index', [
            'accounts' => $accounts,
        ]);
    }

    public function create()
    {
        return Inertia::render('Accounts/Create');
    }

    public function store(StoreAccountRequest $request)
    {
        $account = auth()->user()->accounts()->create($request->validated());

        return redirect()->route('accounts.index')
            ->with('success', 'Compte créé avec succès');
    }

    public function show(Account $account)
    {
        $this->authorize('view', $account);

        $account->load(['transactions' => function ($query) {
            $query->with('category')->latest('date')->limit(10);
        }]);

        return Inertia::render('Accounts/Show', [
            'account' => $account,
        ]);
    }

    public function edit(Account $account)
    {
        $this->authorize('update', $account);

        return Inertia::render('Accounts/Edit', [
            'account' => $account,
        ]);
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        $this->authorize('update', $account);

        $account->update($request->validated());

        return redirect()->route('accounts.index')
            ->with('success', 'Compte mis à jour avec succès');
    }

    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Compte supprimé avec succès');
    }
}
