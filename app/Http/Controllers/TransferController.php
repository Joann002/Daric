<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransferRequest;
use App\Models\Transfer;
use Inertia\Inertia;

class TransferController extends Controller
{
    public function index()
    {
        $transfers = Transfer::with(['fromAccount', 'toAccount'])
            ->whereHas('fromAccount', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest('date')
            ->paginate(20);

        return Inertia::render('Transfers/Index', [
            'transfers' => $transfers,
        ]);
    }

    public function create()
    {
        $accounts = auth()->user()->accounts;

        return Inertia::render('Transfers/Create', [
            'accounts' => $accounts,
        ]);
    }

    public function store(StoreTransferRequest $request)
    {
        Transfer::create($request->validated());

        return redirect()->route('transfers.index')
            ->with('success', 'Transfert effectué avec succès');
    }

    public function destroy(Transfer $transfer)
    {
        if ($transfer->fromAccount->user_id !== auth()->id()) {
            abort(403);
        }

        $transfer->delete();

        return redirect()->route('transfers.index')
            ->with('success', 'Transfert supprimé avec succès');
    }
}
