<?php

namespace Fuganholi\MercosIntegration\Dto\Order;

use Fuganholi\MercosIntegration\Dto\Serializable;

class Extra extends Serializable
{
    public function __construct(
        public ?int $campo_extra_id = null,
        public ?string $nome = null,
        public ?string $tipo = null,
        public ?string $valor_texto = null,
        public ?string $valor_data = null,
        public ?float $valor_decimal = null,
        public ?string $valor_hora = null,
        public ?array $valor_lista = null,
        public mixed $valor = null,
    ) {
        //
    }
}