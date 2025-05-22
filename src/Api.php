<?php

namespace Fuganholi\MercosIntegration;

use Fuganholi\MercosIntegration\Dto\ApiConfig;
use Fuganholi\MercosIntegration\Http\Client;
use Fuganholi\MercosIntegration\Traits\CarrierEntity;
use Fuganholi\MercosIntegration\Traits\CategoryEntity;
use Fuganholi\MercosIntegration\Traits\CustomerEntity;
use Fuganholi\MercosIntegration\Traits\OrderEntity;
use Fuganholi\MercosIntegration\Traits\ProductEntity;
use Fuganholi\MercosIntegration\Traits\StockEntity;
use Fuganholi\MercosIntegration\Traits\UserEntity;

class Api extends Client
{
    use CategoryEntity, UserEntity, CarrierEntity, CustomerEntity, ProductEntity, StockEntity, OrderEntity;

    private static $API_URL = [
        ApiConfig::PRODUCTION_ENVIRONMENT => 'https://app.mercos.com/api',
        ApiConfig::SANDBOX_ENVIRONMENT => 'https://sandbox.mercos.com/api',
    ];

    public function __construct(private ApiConfig $config) {
        //
    }

    public function setEnvironment(string $environment): void
    {
        $this->config->setEnvironment($environment);
    }

    public function setApplicationToken(string $applicationToken): void
    {
        $this->config->setApplicationToken($applicationToken);
    }

    public function setCompanyToken(string $companyToken): void
    {
        $this->config->setCompanyToken($companyToken);
    }

    public function getConfig(mixed $key = null): ApiConfig
    {
        $config = $this->config;

        if ($key) $config = $config->$key;

        return $config;
    }

    public function getFullUrl(string $path): string
    {
        $baseUrl = self::$API_URL[$this->config->environment];

        if (!preg_match("/^\//", $path)) $path = "/$path";

        return ($baseUrl . $path);
    }

    protected function getDefaultHeaders(): array
    {
        $headers = parent::getDefaultHeaders();

        return array_merge($headers, [
            "ApplicationToken: " . $this->config->applicationToken,
            "CompanyToken: ". $this->config->companyToken
        ]);
    }
}