<?php
/*
 * Configuração para criar filas automaticamente
 *
 * name: nome da fila
 * delay (padrão 0): delay em segundos para a fila
 * visibility_timeout (padrão 35): timeout de visibilidade em segundos, ideal ser um pouco maior que --timeout
 * max_receive_count (padrão 7): quantidade de vezes que a fila pode ser recebida antes de ser enviada para o DLQ
 */
return [];