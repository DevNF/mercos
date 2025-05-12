<?php

namespace Fuganholi\MercosIntegration\Dto\Customer;

use Fuganholi\MercosIntegration\Dto\Serializable;

class LimiteCredito extends Serializable
{
    public function __construct(
        public ?float $limite_disponivel = null,
        public ?float $limite_total = null
    ) {
        //
    }
}