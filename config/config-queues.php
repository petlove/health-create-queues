<?php
/*
 * Configuração para criar filas automaticamente
 */
return [
    /**
     * Nome do aplicativo
     */
    'app' => 'app_name',

    /**
     * Ambiente
     */
    'env' => config('app.env', 'dev'),

    /**
     * Filas a serem criadas
     * Possiveis valores:
     * - name: nome da fila
     * - delay: delay em segundos para a fila (default 0)
     * - visibility_timeout: timeout de visibilidade em segundos (default 35) ideal ser um pouco maior que --timeout do consumidor do Laravel
     * - max_receive_count: quantidade de vezes que a fila pode ser recebida antes de ser enviada para o DLQ (default 7) ideal ser o mesmo valor do --tries do consumidor do Laravel
     */
    'queues' => [],
];