<?php

namespace Fuganholi\MercosIntegration\Dto\Customer;

use Fuganholi\MercosIntegration\Dto\Serializable;

class Email extends Serializable
{
    public function __construct(
        public ?int $id = null,
        public ?string $tipo = null,
        public ?string $email = null
    ) {
        //
    }
}