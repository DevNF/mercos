<?php

namespace Fuganholi\MercosIntegration\Dto\Category;

use Fuganholi\MercosIntegration\Dto\Validable;

class Categoria extends Validable
{
    protected static array $casts = [
        'ultima_alteracao' => 'date:Y-m-d H:i:s'
    ];

    protected array $fieldsRules = [
        'nome' => 'required|max:50'
    ];

    public function __construct(
        public ?int $id = null,
        public ?string $nome = null,
        public ?int $categoria_pai_id = null,
        public ?\DateTime $ultima_alteracao = null,
        public ?bool $excluido = null
    ) {
        //
    }
}