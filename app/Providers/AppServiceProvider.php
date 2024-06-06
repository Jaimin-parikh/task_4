<?php

namespace App\Providers;

use App\Models\StockInward;
use App\Models\StockOutward;
use App\Observers\StockInwardObserver;
use App\Observers\StockOutwardObserver;
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
    public function boot()
    {
        StockInward::observe(StockInwardObserver::class);
        StockOutward::observe(StockOutwardObserver::class);
    }
}
