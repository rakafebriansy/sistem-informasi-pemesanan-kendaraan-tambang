<?php

namespace App\Providers;

use App\Http\Responses\LoginWithUsername;
use App\Models\Employee;
use Filament\Http\Responses\Auth\LoginResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Authenticatable::class, Employee::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
