<?php

namespace Fuganholi\MercosIntegration\Dto\Product\Stock;

use Fuganholi\MercosIntegration\Dto\Validable;

class Estoque extends Validable
{
    protected array $fieldsRules = [
        'produto_id' => 'required',
        'novo_saldo' => 'required|max:9999999.99'
    ];

    public function __construct(
        public ?int $produto_id = null,
        public ?float $novo_saldo = null
    ) {
        //
    }
}