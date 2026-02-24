<?php

namespace App\Providers;

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
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Share Dinas data globally for logo, app_name, favicon across all pages
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            static $dinas = null;
            if ($dinas === null) {
                $dinas = \App\Models\Dinas::first();
            }
            $view->with('globalDinas', $dinas);
        });
    }
}
