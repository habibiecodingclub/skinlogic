<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use App\Models\Pesanan;
use App\Policies\PesananPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $policies = [
    \App\Models\Pesanan::class => \App\Policies\PesananPolicy::class,
];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
         $this->registerPolicies();
    }
}
