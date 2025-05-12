<?php

namespace Fuganholi\MercosIntegration\Dto\Order;

use Fuganholi\MercosIntegration\Dto\Serializable;

class DescontoPromocao extends Serializable
{
    public function __construct(
        public ?int $regra_id = null,
        public ?float $desconto = null,
    ) {
        //
    }
}