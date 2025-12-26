<?php

declare(strict_types=1);

namespace JulioCCorreia\ReverbHooks;

use Illuminate\Support\ServiceProvider;

class ReverbHooksServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('reverb-hooks', fn ($app): ReverbHooksManager => new ReverbHooksManager($app));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
