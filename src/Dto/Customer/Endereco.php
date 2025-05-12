<?php

namespace Fuganholi\MercosIntegration\Dto\Customer;

use Fuganholi\MercosIntegration\Dto\Serializable;

class Endereco extends Serializable
{
    public function __construct(
        public ?string $cep = null,
        public ?string $endereco = null,
        public ?string $numero = null,
        public ?string $complemento = null,
        public ?string $bairro = null,
        public ?string $cidade = null,
        public ?string $estado = null,
    ) {
        //
    }
}