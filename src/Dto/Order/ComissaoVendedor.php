<?php

namespace Fuganholi\MercosIntegration\Dto\Order;

use Fuganholi\MercosIntegration\Dto\Serializable;

class ComissaoVendedor extends Serializable
{
    public function __construct(
        public ?int $vendedor_id = null,
        public ?float $percentual = null
    ) {
        //
    }
}