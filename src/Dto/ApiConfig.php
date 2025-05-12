<?php

namespace Fuganholi\MercosIntegration\Dto;

use InvalidArgumentException;

class ApiConfig
{
    public const PRODUCTION_ENVIRONMENT = 'production';
    public const SANDBOX_ENVIRONMENT = 'sandbox';

    public function __construct(
        public string $environment,
        public string $applicationToken = '',
        public string $companyToken = ''
    ) {
        $this->validEnvironment($this->environment);
    }

    private function validEnvironment(string $environment): void
    {
        if (!in_array($environment, [ApiConfig::PRODUCTION_ENVIRONMENT, ApiConfig::SANDBOX_ENVIRONMENT])) {
            $validValues = ApiConfig::PRODUCTION_ENVIRONMENT . ' or ' . ApiConfig::SANDBOX_ENVIRONMENT;
            throw new InvalidArgumentException("environment attribute accept only values: " . $validValues);
        }
    }

    public function setEnvironment(string $environment): void
    {
        $this->validEnvironment($environment);

        $this->environment = $environment;
    }

    public function setApplicationToken(string $applicationToken): void
    {
        $this->applicationToken = $applicationToken;
    }

    public function setCompanyToken(string $companyToken): void
    {
        $this->companyToken = $companyToken;
    }
}