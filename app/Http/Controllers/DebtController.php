<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDebtRequest;
use App\Http\Requests\UpdateDebtRequest;
use App\Models\Debt;
use Inertia\Inertia;

class DebtController extends Controller
{
    public function index()
    {
        $debts = auth()->user()->debts()
            ->latest('created_at')
            ->get()
            ->map(function ($debt) {
                return [
                    'id' => $debt->id,
                    'person_name' => $debt->person_name,
                    'amount' => $debt->amount,
                    'paid_amount' => $debt->paid_amount,
                    'remaining_amount' => $debt->remaining_amount,
                    'direction' => $debt->direction,
                    'status' => $debt->status,
                    'due_date' => $debt->due_date,
                    'description' => $debt->description,
                    'created_at' => $debt->created_at,
                ];
            });

        return Inertia::render('Debts/Index', [
            'debts' => $debts,
        ]);
    }

    public function create()
    {
        return Inertia::render('Debts/Create');
    }

    public function store(StoreDebtRequest $request)
    {
        auth()->user()->debts()->create($request->validated());

        return redirect()->route('debts.index')
            ->with('success', 'Dette/Prêt créé avec succès');
    }

    public function edit(Debt $debt)
    {
        $this->authorize('update', $debt);

        return Inertia::render('Debts/Edit', [
            'debt' => $debt,
        ]);
    }

    public function update(UpdateDebtRequest $request, Debt $debt)
    {
        $this->authorize('update', $debt);

        $debt->update($request->validated());

        return redirect()->route('debts.index')
            ->with('success', 'Dette/Prêt mis à jour avec succès');
    }

    public function destroy(Debt $debt)
    {
        $this->authorize('delete', $debt);

        $debt->delete();

        return redirect()->route('debts.index')
            ->with('success', 'Dette/Prêt supprimé avec succès');
    }
}
