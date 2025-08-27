<?php

namespace Petlove\HealthCreateQueues;

use Illuminate\Support\ServiceProvider;
use Petlove\HealthCreateQueues\Console\Commands\CreateQueuesCommand;

class HealthCreateQueuesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/create-queues.php', 'health-create-queues'
        );
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/create-queues.php' => config_path('create-queues.php'),
            ], 'health-create-queues');

            $this->commands([
                CreateQueuesCommand::class,
            ]);
        }
    }
}
