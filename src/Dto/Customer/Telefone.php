<?php

namespace Fuganholi\MercosIntegration\Dto\Customer;

use Fuganholi\MercosIntegration\Dto\Serializable;

class Telefone extends Serializable
{
    public function __construct(
        public ?int $id = null,
        public ?string $tipo = null,
        public ?string $numero = null
    ) {
        //
    }
}