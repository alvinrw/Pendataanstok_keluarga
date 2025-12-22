<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\DataPenjualan;
use App\Models\Panen;
use App\Models\Pengeluaran;
use App\Models\KematianAyam;
use App\Observers\DataPenjualanObserver;
use App\Observers\PanenObserver;
use App\Observers\PengeluaranObserver;
use App\Observers\KematianAyamObserver;

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
        Paginator::useBootstrapFive();

        // Register Model Observers
        DataPenjualan::observe(DataPenjualanObserver::class);
        Panen::observe(PanenObserver::class);
        Pengeluaran::observe(PengeluaranObserver::class);
        KematianAyam::observe(KematianAyamObserver::class);
    }
}