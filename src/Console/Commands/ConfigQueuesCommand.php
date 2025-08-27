<?php

namespace Petlove\HealthConfigQueues\Console\Commands;

use Aws\Sqs\Exception\SqsException;
use Aws\Sqs\SqsClient;
use Illuminate\Console\Command;

class ConfigQueuesCommand extends Command
{
    protected $signature = 'health:config-queues';

    protected $description = 'Cria automaticamente filas no SQS';

    private const VISIBILITY_TIMEOUT = 35;
    private const MAX_RECEIVE_COUNT = 7;
    private const DELAY = 0;

    private ?SqsClient $sqsClient = null;

    public function __construct()
    {
        parent::__construct();

        $this->sqsClient = $this->getSqsClient();
    }

    public function handle()
    {
        $queues = $this->queues();

        if (empty($queues)) {
            $this->error('Nenhuma fila configurada, verifique o arquivo config/config-queues.php');
            return;
        }

        foreach ($queues as $queue) {
            $queueName = $queue['name'];

            $queueUrl = $this->queueExists($queueName);

            $dlqName = $queueName . '_dlq';

            $dlqUrl = $this->queueExists($dlqName);

            if (!$dlqUrl) {
                $dlqResult = $this->sqsClient->createQueue([
                    'QueueName' => $dlqName,
                ]);
                $dlqUrl = $dlqResult->get('QueueUrl');
            }

            $dlqArn = $this->sqsClient->getQueueAttributes([
                'QueueUrl' => $dlqUrl,
                'AttributeNames' => ['QueueArn'],
            ])->get('Attributes')['QueueArn'];

            $redrivePolicy = json_encode([
                'deadLetterTargetArn' => $dlqArn,
                'maxReceiveCount' => (string) ($queue['max_receive_count'] ?? self::MAX_RECEIVE_COUNT),
            ]);

            $attributes = [
                'DelaySeconds' => (string) ($queue['delay'] ?? self::DELAY),
                'VisibilityTimeout' => (string) ($queue['visibility_timeout'] ?? self::VISIBILITY_TIMEOUT),
                'RedrivePolicy' => $redrivePolicy,
            ];

            if (!$queueUrl) {
                $result = $this->sqsClient->createQueue([
                    'QueueName' => $queueName,
                    'Attributes' => $attributes,
                ]);
                $this->info('Fila criada com sucesso: ' . $result->get('QueueUrl'));
            } else {
                $this->sqsClient->setQueueAttributes([
                    'QueueUrl' => $queueUrl,
                    'Attributes' => $attributes,
                ]);
                $this->info('Fila atualizada com sucesso: ' . $queueUrl);
            }
        }
    }

    private function queueExists(string $queueName): ?string
    {
        try {
            $result = $this->sqsClient->getQueueUrl([
                'QueueName' => $queueName,
            ]);

            return $result->get('QueueUrl');
        } catch (SqsException $e) {
            if ($e->getAwsErrorCode() === 'AWS.SimpleQueueService.NonExistentQueue') {
                return null;
            }

            throw $e;
        }
    }

    private function getSqsClient(): SqsClient
    {
        $config = [
            'region' => config('queue.connections.sqs.region'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('queue.connections.sqs.key'),
                'secret' => config('queue.connections.sqs.secret'),
            ],
        ];

        if (config('app.env') == 'local' || config('app.env') == 'dev') {
            // Localmente usamos o localstack, ele precisa desse endpoint para funcionar
            $config['endpoint'] = config('queue.connections.sqs.endpoint');
        }

        return new SqsClient($config);
    }

    private function queues(): array
    {
        return config('config-queues');
    }
}
