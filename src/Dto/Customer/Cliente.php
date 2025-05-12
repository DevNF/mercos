<?php

namespace Fuganholi\MercosIntegration\Dto\Customer;

use Fuganholi\MercosIntegration\Dto\Validable;

class Cliente extends Validable
{
    protected static array $casts = [
        'ultima_alteracao' => 'date:Y-m-d H:i:s'
    ];

    protected array $fieldsRules = [
        'razao_social'                          => 'required|max:100',
        'nome_fantasia'                         => 'nullable|max:100',
        'tipo'                                  => 'required|in:J,F',
        'cnpj'                                  => 'nullable|between:11,14',
        'inscricao_estadual'                    => 'nullable|max:30',
        'suframa'                               => 'nullable|max:20',
        'rua'                                   => 'nullable|max:100',
        'numero'                                => 'nullable|max:100',
        'complemento'                           => 'nullable|max:50',
        'cep'                                   => 'nullable|max:9',
        'bairro'                                => 'nullable|max:30',
        'cidade'                                => 'nullable|max:50',
        'estado'                                => 'nullable|max:2',
        'observacao'                            => 'nullable|max:500',
        'nome_excecao_fiscal'                   => 'nullable|max:20',
        'emails.*.email'                        => 'nullable|max:75',
        'telefones.*.numero'                    => 'nullable|max:30',
        'contatos.*.emails.*.email'             => 'nullable|max:75',
        'contatos.*.telefones.numero'           => 'nullable|max:30',
        'numero_excecao_fiscal'                 => 'nullable|max:20',
        'enderecos_adicionais.*.cep'            => 'nullable|max:9',
        'enderecos_adicionais.*.endereco'       => 'nullable|max:200',
        'enderecos_adicionais.*.numero'         => 'nullable|max:100',
        'enderecos_adicionais.*.complemento'    => 'nullable|max:200',
        'enderecos_adicionais.*.bairro'         => 'nullable|max:200',
        'enderecos_adicionais.*.cidade'         => 'nullable|max:200',
        'enderecos_adicionais.*.estado'         => 'nullable|maz:2'
    ];

    /** @var Email[] */
    protected array $emails = [];
    /** @var Telefone[] */
    protected array $telefones = [];
    /** @var Contato[] */
    protected array $contatos = [];
    /** @var Endereco[] */
    protected array $enderecos_adicionais = [];
    /** @var LimiteCredito[] */
    protected array $limite_credito = [];

    public function __construct(
        public ?int $id = null,
        public ?string $razao_social = null,
        public ?string $nome_fantasia = null,
        public ?string $tipo = null,
        public ?string $cnpj = null,
        public ?string $inscricao_estadual = null,
        public ?string $suframa = null,
        public ?string $rua = null,
        public ?string $numero = null,
        public ?string $complemento = null,
        public ?string $cep = null,
        public ?string $bairro = null,
        public ?string $cidade = null,
        public ?string $estado = null,
        public ?string $observacao = null,
        public ?string $nome_excecao_fiscal = null,
        public ?int $criador_id = null,
        public ?int $segmento_id = null,
        public ?int $rede_id = null,
        public ?bool $bloqueado_b2b = null,
        public ?bool $excluido = null,
        public ?bool $bloqueado = null,
        public ?int $motivo_bloqueio_id = null,
        public ?\DateTime $ultima_alteracao = null
    ) {
        //
    }

    public function addEmail(Email $email): void
    {
        $this->emails[] = $email;
    }

    public function addTelefone(Telefone $telefone): void
    {
        $this->telefones[] = $telefone;
    }

    public function addContato(Contato $contato): void
    {
        $this->contatos[] = $contato;
    }

    public function addEnderecoAdicional(Endereco $endereco): void
    {
        $this->enderecos_adicionais[] = $endereco;
    }

    public function addLimiteCredito(LimiteCredito $limite): void
    {
        $this->limite_credito[] = $limite;
    }

    /**
     * @return Email[]
     */
    public function getEmails(): array
    {
        return $this->emails;
    }

    /**
     * @return Telefone[]
     */
    public function getTelefones(): array
    {
        return $this->telefones;
    }

    /**
     * @return Contato[]
     */
    public function getContatos(): array
    {
        return $this->contatos;
    }

    /**
     * @return Endereco[]
     */
    public function getEnderecosAdicionais(): array
    {
        return $this->enderecos_adicionais;
    }

    /**
     * @return LimiteCredito[]
     */
    public function getLimiteCredito(): array
    {
        return $this->limite_credito;
    }

    public static function create(\stdClass $c): static
    {
        $customer = parent::create($c);

        foreach ($c->emails as $email) $customer->addEmail(Email::create($email));
        foreach ($c->telefones as $telefone) $customer->addTelefone(Telefone::create($telefone));
        foreach ($c->contatos as $contato) $customer->addContato(Contato::create($contato));
        foreach ($c->enderecos_adicionais as $enderecoAdicional) $customer->addEnderecoAdicional(Endereco::create($enderecoAdicional));
        foreach ($c->limite_credito as $limiteCredito) $customer->addLimiteCredito(LimiteCredito::create($limiteCredito));

        return $customer;
    }
}