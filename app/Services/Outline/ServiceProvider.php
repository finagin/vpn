<?php

namespace App\Services\Outline;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/lang', 'outline');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\BackupCommand::class,
                Commands\RestoreCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->app->singleton(
            Contract::class,
            Service::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<array-key, class-string>
     */
    public function provides(): array
    {
        return [Contract::class];
    }
}
