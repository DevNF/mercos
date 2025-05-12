<?php

namespace Fuganholi\MercosIntegration\Dto\Product;

use Fuganholi\MercosIntegration\Dto\Validable;

class Produto extends Validable
{
    protected static array $casts = [
        'ultima_alteracao' => 'date:Y-m-d H:i:s'
    ];

    protected array $fieldsRules = [
        'codigo'    => 'nullable|max:50',
        'nome'      => 'required|max:100',
        'preco_tabela' => 'required',
        'tipo_ipi'  => 'nullable|in:P,V',
        'moeda' => 'nullable|in:0,1,2',
        'unidade' => 'nullable|max:10',
        'saldo_estoque' => 'nullable|max:9999999.99',
        'observacoes' => 'nullable|max:5000'
    ];

    public function __construct(
        public ?int $id = null,
        public ?string $codigo = null,
        public ?string $nome = null,
        public ?float $comissao = null,
        public ?float $preco_tabela = null,
        public ?float $preco_minimo = null,
        public ?float $ipi = null,
        public ?string $tipo_ipi = null,
        public ?float $st = null,
        public ?string $moeda = null,
        public ?string $unidade = null,
        public ?float $saldo_estoque = null,
        public ?string $observacoes = null,
        public ?bool $excluido = null,
        public ?bool $ativo = null,
        public ?int $categoria_id = null,
        public ?string $codigo_ncm = null,
        public ?float $multiplo = null,
        public ?float $peso_bruto = null,
        public ?float $largura = null,
        public ?float $altura = null,
        public ?float $comprimento = null,
        public ?bool $peso_dimensoes_unitario = null,
        public ?bool $exibir_no_b2b = null,
        public ?bool $precos_especificos = null,
        public ?\DateTime $ultima_alteracao = null
    ) {
        //
    }
}