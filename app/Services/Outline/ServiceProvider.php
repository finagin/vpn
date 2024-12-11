<?php

namespace App\Services\Outline;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
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
