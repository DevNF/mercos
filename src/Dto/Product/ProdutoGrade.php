<?php

namespace Fuganholi\MercosIntegration\Dto\Product;

use Fuganholi\MercosIntegration\Dto\Serializable;

class ProdutoGrade extends Serializable
{
    /** @var int[] */
    protected array $itens_variacoes_ids = [];
    /** @var array<string, string>[] */
    protected array $itens_variacoes_nomes = [];

    public function __construct(
        public ?int $id = null,
        public ?string $codigo = null,
        public ?bool $ativo = null,
        public ?bool $excluido = null,
        public ?bool $exibir_no_b2b = null,
        public ?float $preco_tabela = null,
    ) {
        //
    }

    public static function create(\stdClass $g): static
    {
        $grade = parent::create($g);

        foreach (($g?->itens_variacoes_ids ?? []) as $itemId) {
            $grade->addItemVariacaoId($itemId);
        }

        foreach (($g?->itens_variacoes_nomes ?? []) as $itemNome) {
            $grade->addItemVariacaoNome((array) $itemNome);
        }

        return $grade;
    }

    public function toArray(): array
    {
        $serialized = parent::toArray();

        foreach (['itens_variacoes_ids', 'itens_variacoes_nomes'] as $list) {
            if (empty($serialized[$list])) unset($serialized[$list]);
        }

        return $serialized;
    }

    public function addItemVariacaoId(int $itemVariacaoId): void
    {
        $this->itens_variacoes_ids[] = $itemVariacaoId;
    }

    /**
     * @param array<string, string> $itemVariacaoNome
     */
    public function addItemVariacaoNome(array $itemVariacaoNome): void
    {
        $this->itens_variacoes_nomes[] = $itemVariacaoNome;
    }

    /**
     * @return int[]
     */
    public function getItensVariacoesIds(): array
    {
        return $this->itens_variacoes_ids;
    }

    /**
     * @return array<string, string>[]
     */
    public function getItensVariacoesNomes(): array
    {
        return $this->itens_variacoes_nomes;
    }
}
