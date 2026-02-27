<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use kreait\Firebase\Factory;
class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('firebase', function() {
            return (new Factory)
                ->withServiceAccount(config('services.firebase.credentials'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
