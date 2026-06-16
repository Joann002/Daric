<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportTransactionsCsv(Request $request)
    {
        $query = Transaction::with(['account', 'category'])
            ->whereHas('account', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest('date');

        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->account_id) {
            $query->where('account_id', $request->account_id);
        }

        $transactions = $query->get();

        $filename = 'transactions_' . Carbon::now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM pour Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // En-têtes
            fputcsv($file, ['Date', 'Compte', 'Catégorie', 'Type', 'Montant', 'Description'], ';');

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->date->format('Y-m-d'),
                    $transaction->account->name,
                    $transaction->category->name,
                    $transaction->type === 'income' ? 'Revenu' : 'Dépense',
                    $transaction->amount,
                    $transaction->description ?? '',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
