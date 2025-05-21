<?php

namespace Fuganholi\MercosIntegration\Dto\Carrier;

use Fuganholi\MercosIntegration\Dto\Validable;
use Fuganholi\MercosIntegration\Helpers\Formatter;

class Transportadora extends Validable
{
    protected static array $casts = [
        'ultima_alteracao' => 'date:Y-m-d H:i:s'
    ];

    protected array $fieldsRules = [
        'nome'                      => 'required|max:100',
        'cidade'                    => 'nullable|max:50',
        'estado'                    => 'nullable|max:2',
        'informacoes_adicionais'    => 'nullable|max:2',
        'telefones.*.numero'        => 'nullable|max:30',
        'telefones.*.tipo'          => 'nullable|in:C,F,O,R,T'
    ];

    /** @var Telefone[] */
    protected array $telefones = [];

    public function __construct(
        public ?int $id = null,
        public ?string $nome = null,
        public ?string $cidade = null,
        public ?string $estado = null,
        public ?string $informacoes_adicionais = null,
        public ?bool $excluido = null,
        public ?\DateTime $ultima_alteracao = null,
    ) {
        //
    }

    public function addTelefone(Telefone $telefone): void
    {
        $this->telefones[] = $telefone;
    }

    /**
     * @return Telefone[]
     */
    public function getTelefones(): array
    {
        return $this->telefones;
    }

    public static function create(\stdClass $c): static
    {
        $carrier = new self(
            $c->id,
            $c->nome,
            $c->cidade,
            $c->estado,
            $c?->informacoesAdicionais ?? null,
            $c->excluido,
            Formatter::formatDateTime($c->ultima_alteracao),
        );

        foreach ($c->telefones as $telefone) {
            $carrier->addTelefone(Telefone::create($telefone));
        }

        return $carrier;
    }
}