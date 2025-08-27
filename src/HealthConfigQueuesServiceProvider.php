<?php

namespace Petlove\HealthConfigQueues;

use Illuminate\Support\ServiceProvider;
use Petlove\HealthConfigQueues\Console\Commands\ConfigQueuesCommand;

class HealthConfigQueuesServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
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
