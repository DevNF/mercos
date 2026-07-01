<?php

namespace Fuganholi\MercosIntegration\Dto\Variation;

use Fuganholi\MercosIntegration\Dto\Serializable;

class ItemVariacao extends Serializable
{
    // Mercos API rejects 'ordem' in itens_variacao items (only valid on the variation itself).
    protected array $hidden = ['ordem'];

    public function __construct(
        public ?int $id = null,
        public ?string $nome = null,
        public ?int $ordem = null,
        public ?string $cor = null,
        public ?bool $excluido = null,
        public ?string $imagem_url = null,
        public ?string $imagem_base64 = null,
    ) {
        //
    }
}
