<?php

namespace App\Providers;

use App\Http\Responses\LoginWithUsername;
use Filament\Http\Responses\Auth\LoginResponse;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->singleton(LoginResponse::class, LoginWithUsername::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
