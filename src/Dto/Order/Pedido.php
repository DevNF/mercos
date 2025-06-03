<?php

namespace Fuganholi\MercosIntegration\Dto\Order;

use Fuganholi\MercosIntegration\Dto\Validable;

class Pedido extends Validable
{
    const STATUS_CANCELED = '0';
    const STATUS_BUDGET = '1';
    const STATUS_ORDER = '2';

    protected static array $casts = [
        'data_emissao'      => 'date:Y-m-d H:i:s',
        'data_criacao'      => 'date:Y-m-d H:i:s',
        'ultima_alteracao'  => 'date:Y-m-d H:i:s'
    ];

    protected array $fieldsRules = [];

    /** @var Item[] */
    protected array $itens = [];
    /** @var string[] */
    protected array $cliente_telefone = [];
    /** @var string[] */
    protected array $cliente_email = [];
    /** @var ComissaoVendedor[] */
    protected array $comissoes_vendedores = [];
    /** @var Extra[] */
    protected array $extra = [];

    public function __construct(
        public ?int $id = null,
        public ?int $cliente_id = null,
        public ?int $pedido_origem_id = null,
        public ?string $cliente_razao_social = null,
        public ?string $cliente_nome_fantasia = null,
        public ?string $cliente_cnpj = null,
        public ?string $cliente_inscricao_estadual = null,
        public ?string $cliente_rua = null,
        public ?string $cliente_numero = null,
        public ?string $cliente_complemento = null,
        public ?string $cliente_cep = null,
        public ?string $cliente_bairro = null,
        public ?string $cliente_cidade = null,
        public ?string $cliente_estado = null,
        public ?string $cliente_suframa = null,
        public ?int $representada_id = null,
        public ?string $representada_nome_fantasia = null,
        public ?string $representada_razao_social = null,
        public ?int $transportadora_id = null,
        public ?string $transportadora_nome = null,
        public ?int $criador_id = null,
        public ?string $nome_contato = null,
        public ?string $status = null,
        public ?int $numero = null,
        public ?string $rastreamento = null,
        public ?float $valor_frete = null,
        public ?float $total = null,
        public ?string $condicao_pagamento = null,
        public ?int $condicao_pagamento_id = null,
        public ?int $tipo_pedido_id = null,
        public ?int $forma_pagamento_id = null,
        public ?\DateTime $data_emissao = null,
        public ?string $observacoes = null,
        public ?int $status_faturamento = null,
        public ?int $status_custom_id = null,
        public ?int $status_b2b = null,
        public ?bool $possui_informacao_pagamento = null,
        public ?\DateTime $data_criacao = null,
        public ?string $prazo_entrega = null,
        public ?string $modalidade_entrega_nome = null,
        public ?float $percentual_total_comissao_pedido = null,
        public ?string $cupom_de_desconto = null,
        public ?EnderecoEntrega $endereco_entrega = null,
        public ?\DateTime $ultima_alteracao = null,
    ) {
        //
    }

    public static function create(\stdClass $p): static
    {
        $pedido = parent::create($p);
        if ($p?->endereco_entrega) $pedido->endereco_entrega = EnderecoEntrega::create($p?->endereco_entrega);

        foreach (($p?->itens ?? []) as $item) $pedido->addItem(Item::create($item));
        foreach (($p?->cliente_telefone ?? []) as $telefone) $pedido->addClienteTelefone($telefone);
        foreach (($p?->cliente_email ?? []) as $email) $pedido->addClienteEmail($email);
        foreach (($p?->comissoes_vendedores ?? []) as $comissao) $pedido->addComissaoVendedor(ComissaoVendedor::create($comissao));
        foreach (($p?->extra ?? []) as $campoExtra) $pedido->addCampoExtra(Extra::create($campoExtra));

        return $pedido;
    }

    public function addItem(Item $item): void
    {
        $this->itens[] = $item;
    }

    public function addClienteTelefone(string $telefone): void
    {
        $this->cliente_telefone[] = $telefone;
    }

    public function addClienteEmail(string $email): void
    {
        $this->cliente_email[] = $email;
    }

    public function addComissaoVendedor(ComissaoVendedor $comissao): void
    {
        $this->comissoes_vendedores[] = $comissao;
    }

    public function addCampoExtra(Extra $campoExtra): void
    {
        $this->extra[] = $campoExtra;
    }

    /**
     * @return Item[]
     */
    public function getItens(): array
    {
        return $this->itens;
    }

    /**
     * @return string[]
     */
    public function getClienteTelefones(): array
    {
        return $this->cliente_telefone;
    }

    /**
     * @return string[]
     */
    public function getClienteEmails(): array
    {
        return $this->cliente_email;
    }

    /**
     * @return ComissaoVendedor[]
     */
    public function getComissoesVendedores(): array
    {
        return $this->comissoes_vendedores;
    }

    /**
     * @return Extra[]
     */
    public function getCamposExtra(): array
    {
        return $this->extra;
    }

    public function getSubtotal(): float
    {
        return array_reduce($this->itens, function ($subtotal, $item) {
            $subtotal += $item->getTotalSemDesconto();

            return $subtotal;
        }, 0.0);
    }

    public function getDesconto(): float
    {
        return array_reduce($this->itens, function ($desconto, $item) {
            $desconto += $item->getTotalDesconto();

            return $desconto;
        }, 0.0);
    }
}