# Health Config Queues

Este pacote tem como objetivo automatizar a criação de filas no SQS como base na execução de um comando artisan.

## Instalação

Instalar o pacote usando o composer:

```bash
composer require petlove/health-config-queues
```

Após instalar o pacote você pode publicar a configuração:

```bash
php artisan vendor:publish --tag=health-config-queues
```

## Configuração

As filas são criadas com base na configuração do arquivo `config/config-queues.php`.

Exemplo para criar uma fila com o nome `bill_paid` e `send_email`:

```php
<?php

return [
    [
        'name' => 'bill_paid'
    ],
    [
        'name' => 'send_email'
    ],
];
```

### Parâmetros de Configuração

Junto do nome da fila podem ser passados algumas configurações aceitas no SQS da AWS:

- **name**: nome da fila
- **delay**: delay em segundos para a fila (default 0)
- **visibility_timeout**: timeout de visibilidade em segundos (default 35) - ideal ser um pouco maior que `--timeout` do consumidor do Laravel
- **max_receive_count**: quantidade de vezes que a fila pode ser recebida antes de ser enviada para o DLQ (default 7) - ideal ser o mesmo valor do `--tries` do consumidor do Laravel

## Uso

Depois de configurar as filas você pode rodar o comando:

```bash
php artisan health:config-queues
```

Esse comando vai criar todas as filas que estão na configuração.

**Importante**: Esse comando não deleta nenhuma fila do SQS! Mesmo a fila não existindo mais no arquivo de configuração o comando não vai deletar ela.

## Sugestão de Deploy

Uma sugestão é adicionar a execução desse comando no deploy da aplicação, assim tem uma garantia que a fila vai existir ao subir a aplicação, seja ambiente local ou cloud.