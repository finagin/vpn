<?php

namespace App\Providers;

use App\Services\Auth\TelegramGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
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
    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());

        Vite::prefetch(concurrency: 3);

        URL::forceRootUrl('https://vpn.kool.live');
        URL::forceHttps();

        Auth::extend('telegram-init-data', fn (Application $app, string $name, array $config) => new TelegramGuard(
            $app->make('auth')->createUserProvider($config['provider']),
            $app->make('request'),
            $app->make('config')['services.telegram.bot.token']
        ));
    }
}
