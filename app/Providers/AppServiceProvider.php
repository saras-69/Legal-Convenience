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

    protected $policies = [
        Service::class => ServicePolicy::class,
        Booking::class => BookingPolicy::class,
    ];
    public function boot(): void
    {
        //
    }
}
