<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use App\Session\HybridSessionHandler;
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
          $handler = new HybridSessionHandler('central_sessions');
    session_set_save_handler($handler, true);
    session_start();

    Session::extend('hybrid', function ($app) {
    return new HybridSessionHandler('central_sessions');
});
    }
}
