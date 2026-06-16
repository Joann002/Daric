<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Models\Transfer;
use App\Observers\TransactionObserver;
use App\Observers\TransferObserver;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        
        Transaction::observe(TransactionObserver::class);
        Transfer::observe(TransferObserver::class);
    }
}
