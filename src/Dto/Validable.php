<?php

namespace Fuganholi\MercosIntegration\Dto;

use Fuganholi\MercosIntegration\Exceptions\ValidationException;
use Fuganholi\MercosIntegration\Facades\Validation\Validator;
use Fuganholi\MercosIntegration\Helpers\Thrower;

abstract class Validable extends Serializable
{
    protected array $fieldsRules = [];

    protected array $hidden = [
        'fieldsRules'
    ];

    public function validate(): void
    {
        $validate = Validator::validate($this->toArray(), $this->fieldsRules);

        if ($validate->fails()) {
            Thrower::withValidationErrors($validate->errors())
                ->throwException(ValidationException::class, 'Ocorreu um erro ao tentar validar os campos');
        }
    }
}