<?php

namespace Petlove\HealthConfigQueues;

use Illuminate\Support\ServiceProvider;
use Petlove\HealthConfigQueues\Console\Commands\ConfigQueuesCommand;

class HealthConfigQueuesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__.'/config/config-queues.php' => config_path('config-queues.php'),
        ], 'config');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ConfigQueuesCommand::class,
            ]);
        }
    }
}
