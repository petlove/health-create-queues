<?php

namespace Petlove\HealthConfigQueues\Console\Commands;

use Aws\Sqs\SqsClient;
use Illuminate\Console\Command;

class ConfigQueuesCommand extends Command
{
    protected $signature = 'health:config-queues 
                            {--queues= : Lista de filas separadas por vírgula}';

    protected $description = 'Cria automaticamente filas no SQS';

    public function handle()
    {
        $this->info('Configurando filas no SQS');
    }
}
