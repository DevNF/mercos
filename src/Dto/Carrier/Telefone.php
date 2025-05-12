<?php

namespace Fuganholi\MercosIntegration\Dto\Carrier;

use Fuganholi\MercosIntegration\Dto\Serializable;
use InvalidArgumentException;

class Telefone extends Serializable
{
    const ACCEPTED_TYPES = ['C', 'F', 'O', 'R', 'T'];

    public function __construct(
        public ?int $id = null,
        public ?string $numero = null,
        public ?string $tipo = null
    ) {
        if ($this->tipo && !in_array($this->tipo, self::ACCEPTED_TYPES)) {
            throw new InvalidArgumentException("The 'tipo' field expects to receive one of the following values: " . implode(', ', self::ACCEPTED_TYPES));
        }
    }
}