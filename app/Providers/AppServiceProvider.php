<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Policies\TaskPolicy;
use App\Models\Task;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Task::class => TaskPolicy::class,
    ];

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
        //
    }
}
