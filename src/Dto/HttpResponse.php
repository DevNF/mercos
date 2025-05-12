<?php

namespace Fuganholi\MercosIntegration\Dto;

class HttpResponse
{
    public function __construct(
        private string $body,
        private int $code,
        private array $headers,
        private $details = null
    ) {
        //
    }

    public function getResponse(): mixed
    {
        return json_decode($this->body, true);
    }

    public function getResponseJson(): mixed
    {
        return json_decode($this->body);
    }

    public function getOriginalBody()
    {
        return $this->body;
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function getHeaders(?string $key = null): mixed
    {
        if (!$key) return $this->headers;

        return $this->headers[$key] ?? null;
    }

    public function getRequestDetails(): mixed
    {
        return $this->details;
    }

    public function isSuccess(): bool
    {
        return ($this->code >= 200 && $this->code <= 299);
    }
}