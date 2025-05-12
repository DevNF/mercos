<?php

namespace Fuganholi\MercosIntegration\Dto\Customer;

use Fuganholi\MercosIntegration\Dto\Serializable;

class Contato extends Serializable
{
    /** @var Email[] */
    protected array $emails = [];
    /** @var Telefone[] */
    protected array $telefones = [];

    public function __construct(
        public ?string $id = null,
        public ?string $nome = null,
        public ?string $cargo = null,
        public ?bool $excluido = null
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

    public static function create(\stdClass $c): static
    {
        $contact = parent::create($c);

        foreach ($c->emails as $email) $contact->addEmail(Email::create($email));
        foreach ($c->telefones as $telefone) $contact->addTelefone(Telefone::create($telefone));

        return $contact;
    }
}