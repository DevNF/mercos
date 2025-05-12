<?php

namespace Fuganholi\MercosIntegration\Dto\Order;

use Fuganholi\MercosIntegration\Dto\Serializable;

class EnderecoEntrega extends Serializable
{
    public function __construct(
        public ?int $id = null,
        public ?string $cep = null,
        public ?string $endereco = null,
        public ?string $numero = null,
        public ?string $complemento = null,
        public ?string $bairro = null,
        public ?string $cidade = null,
        public ?string $estado = null
    ) {
        //
    }
}