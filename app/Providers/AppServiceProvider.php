<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // Important pour le fix

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void
    {
        // Fix pour les versions anciennes de MySQL
        Schema::defaultStringLength(191);
    }
}
