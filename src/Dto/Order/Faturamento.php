<?php

namespace Fuganholi\MercosIntegration\Dto\Order;

use Fuganholi\MercosIntegration\Dto\Validable;

class Faturamento extends Validable
{
    protected array $fieldsRules = [
        'pedido_id'                 => 'required',
        'valor_faturado'            => 'required|max:999999999.99',
        'data_faturamento'          => 'required|date_format:Y-m-d',
        'numero_nf'                 => 'nullable|max:500',
        'informacoes_adicionais'    => 'nullable|max:500'
    ];

    public function __construct(
        public ?int $pedido_id = null,
        public ?float $valor_faturado = null,
        public ?string $data_faturamento = null,
        public ?string $numero_nf = null,
        public ?string $informacoes_adicionais = null,
        public ?bool $excluido = null
    ) {
        //
    }
}