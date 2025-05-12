<?php

namespace Fuganholi\MercosIntegration\Dto\Order;

use Fuganholi\MercosIntegration\Dto\Serializable;

class Item extends Serializable
{
    /** @var float[] */
    protected array $descontos_do_vendedor = [];
    /** @var DescontoPromocao[] */
    protected array $descontos_de_promocoes = [];
    /** @var DescontoPolitica[] */
    protected array $descontos_de_politicas = [];

    public function __construct(
        public ?int $id = null,
        public ?int $produto_dd = null,
        public ?string $produto_codigo = null,
        public ?string $produto_nome = null,
        public ?int $tabela_preco_id = null,
        public ?float $quantidade = null,
        public ?float $preco_tabela = null,
        public ?float $preco_liquido = null,
        public ?float $cotacao_moeda = null,
        public ?float $desconto_de_cupom = null,
        public ?string $observacoes = null,
        public ?bool $excluido = null,
        public ?float $ipi = null,
        public ?string $tipo_ipi = null,
        public ?float $st = null,
        public ?float $subtotal = null,
        public ?string $grupo_grades = null,
        public ?int $produto_agregador_id = null,
    ) {
        //
    }

    public static function create(\stdClass $i): static
    {
        $item = parent::create($i);

        foreach ($i->descontos_do_vendedor as $desconto) $item->addDescontoDoVendedor($desconto);
        foreach ($i->descontos_de_promocoes as $desconto) $item->addDescontoDePromocao(DescontoPromocao::create($desconto));
        foreach ($i->descontos_de_politicas as $desconto) $item->addDescontoDePolitica(DescontoPolitica::create($desconto));

        return $item;
    }

    public function addDescontoDoVendedor(float $desconto): void
    {
        $this->descontos_do_vendedor[] = $desconto;
    }

    public function addDescontoDePromocao(DescontoPromocao $desconto): void
    {
        $this->descontos_de_promocoes[] = $desconto;
    }

    public function addDescontoDePolitica(DescontoPolitica $desconto): void
    {
        $this->descontos_de_politicas[] = $desconto;
    }
}