<?php

namespace Fuganholi\MercosIntegration\Traits\Http;

use Fuganholi\MercosIntegration\Dto\HttpResponse;

trait Throttleable
{
    private int $throttleLimit = 3;
    private int $throttleOffset = 0;

    public function throttlingRequest(HttpResponse $httpResponse, string $method, ...$args)
    {
        if ($httpResponse->getStatusCode() != 429 || $this->throttleOffset >= $this->throttleLimit) return $httpResponse;

        $this->throttleOffset++;

        $body = $httpResponse->getResponseJson();
        sleep($body->tempo_ate_permitir_novamente);

        return $this->$method(...$args);
    }

    public function resetThrottleOffset(): void
    {
        $this->throttleOffset = 0;
    }
}