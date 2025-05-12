<?php

namespace Fuganholi\MercosIntegration\Facades\Validation;

class ValidationErrorMessages
{
    private array $errors = [];

    public function setError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;

        $this->errors[$field] = array_unique($this->errors[$field]);
    }

    public function messages(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}