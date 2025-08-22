<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Photo;
use App\Policies\PhotoPolicy;

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
        // Guest-safe admin gate
        Gate::define('admin', fn (?User $user) => $user?->role === 'admin');

        // register Photo policy
        Gate::policy(Photo::class, PhotoPolicy::class);
    }
}
