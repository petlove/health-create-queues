<?php

namespace Petlove\HealthConfigQueues;

class Helper
{
    /**
     * Formata o nome da fila
     *
     * @param string $name Nome da fila
     * @param string|null $app Nome do aplicativo
     * @param string|null $env Ambiente
     * @return string
     */
    public static function formatName(string $name, ?string $app = null, ?string $env = null): string
    {
        return strtolower(($app ?? config('config-queues.app')) . '_' . ($env ?? config('config-queues.env')) . '_' . $name);
    }
}
