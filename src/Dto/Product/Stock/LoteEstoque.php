<?php

namespace Fuganholi\MercosIntegration\Dto\Product\Stock;

use Fuganholi\MercosIntegration\Dto\Validable;

class LoteEstoque extends Validable
{
    /** @var Estoque[] */
    private array $estoques = [];

    protected array $fieldsRules = [
        'estoques*.produto_id' => 'required',
        'estoques*.novo_saldo' => 'required|max:9999999.99'
    ];

    public function addEstoque(Estoque $estoque): void
    {
        $this->estoques[] = $estoque;
    }

    /**
     * @return Estoque[]
     */
    public function getEstoques(): array
    {
        return $this->estoques;
    }
}