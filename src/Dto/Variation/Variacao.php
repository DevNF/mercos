<?php

namespace Fuganholi\MercosIntegration\Dto\Variation;

use Fuganholi\MercosIntegration\Dto\Validable;

class Variacao extends Validable
{
    protected static array $casts = [
        'ultima_alteracao' => 'date:Y-m-d H:i:s'
    ];

    protected array $fieldsRules = [
        'nome'  => 'required|max:20',
        'ordem' => 'required',
    ];

    /** @var ItemVariacao[] */
    protected array $itens_variacao = [];

    public function __construct(
        public ?int $id = null,
        public ?string $nome = null,
        public ?int $ordem = null,
        public ?bool $excluido = null,
        public ?\DateTime $ultima_alteracao = null,
    ) {
        //
    }

    public static function create(\stdClass $v): static
    {
        $variacao = parent::create($v);

        foreach (($v?->itens_variacao ?? []) as $item) {
            $variacao->addItemVariacao(ItemVariacao::create($item));
        }

        return $variacao;
    }

    public function addItemVariacao(ItemVariacao $item): void
    {
        $this->itens_variacao[] = $item;
    }

    /**
     * @return ItemVariacao[]
     */
    public function getItensVariacao(): array
    {
        return $this->itens_variacao;
    }

    public function toArray(): array
    {
        $serialized = parent::toArray();

        if (empty($serialized['itens_variacao'])) unset($serialized['itens_variacao']);

        return $serialized;
    }
}
