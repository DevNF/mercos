<?php

namespace Fuganholi\MercosIntegration\Dto\Order;

use Fuganholi\MercosIntegration\Dto\Serializable;

class DescontoPolitica extends Serializable
{
    public function __construct(
        public ?string $slug = null,
        public ?int $regra_id = null,
        public ?float $desconto = null,
    ) {
        //
    }
}