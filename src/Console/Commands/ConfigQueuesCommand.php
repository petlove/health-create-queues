<?php

namespace Petlove\HealthConfigQueues\Console\Commands;

use Aws\Sqs\SqsClient;
use Illuminate\Console\Command;

class ConfigQueuesCommand extends Command
{
    protected $signature = 'health:config-queues';

    protected $description = 'Cria automaticamente filas no SQS';

    public function handle()
    {
        $queues = $this->queues();

        foreach ($queues as $queue) {
            $this->info("Configurando fila: {$queue['name']}");
        }
    }

    private function queues(): array
    {
        return config('config-queues.queues', []);
    }
}
