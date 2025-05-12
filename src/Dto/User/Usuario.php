<?php

namespace Fuganholi\MercosIntegration\Dto\User;

use Fuganholi\MercosIntegration\Dto\Validable;

class Usuario extends Validable
{
    protected static array $casts = [
        'ultima_alteracao' => 'date:Y-m-d H:i:s'
    ];

    public function __construct(
        public ?int $id = null,
        public ?string $nome = null,
        public ?string $email = null,
        public ?string $telefone = null,
        public ?bool $administrador = null,
        public ?bool $acesso_bloqueado = null,
        public ?bool $excluido = null,
        public ?\DateTime $ultima_alteracao = null,
    ) {
        //
    }
}