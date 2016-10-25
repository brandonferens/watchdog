<?php

namespace BrandonFerens\Watchdog;

use BrandonFerens\Watchdog\Command\Kennel;
use BrandonFerens\Watchdog\Command\Patrol;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/watchdog.php' => config_path('watchdog.php'),
            ]
        );
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/watchdog.php', 'watchdog'
        );

        $this->commands(
            [
                Patrol::class,
                Kennel::class,
            ]
        );
    }
}
