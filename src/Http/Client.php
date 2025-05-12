<?php

namespace Fuganholi\MercosIntegration\Http;

use Fuganholi\MercosIntegration\Dto\HttpResponse;
use Fuganholi\MercosIntegration\Dto\HttpParamCollection;
use Fuganholi\MercosIntegration\Helpers\Formatter;

abstract class Client
{
    private bool $activeDebug = false;
    private bool $isUpload = false;

    public function setActiveDebug(bool $activeDebug): void
    {
        $this->activeDebug = $activeDebug;
    }

    protected function setIsUpload(bool $isUpload): void
    {
        $this->isUpload = $isUpload;
    }

    abstract public function getFullUrl(string $path): string;

    protected function getDefaultHeaders(): array
    {
        $accept = $this->isUpload ? '*/*' : 'application/json';
        $contentType = $this->isUpload ? 'multipart/form-data' : 'application/json';

        return [
            "Accept: $accept",
            "Content-Type: $contentType"
        ];
    }

    protected function get(string $path, HttpParamCollection $params = new HttpParamCollection()): HttpResponse
    {
        return $this->execute($this->getFullUrl($path), [], [], $params);
    }

    protected function post(string $path, array $data, HttpParamCollection $params = new HttpParamCollection()): HttpResponse
    {
        $opts = [CURLOPT_POST => true];

        return $this->execute($this->getFullUrl($path), $data, $opts, $params);
    }

    protected function put(string $path, array $data, HttpParamCollection $params = new HttpParamCollection()): HttpResponse
    {
        $opts = [CURLOPT_CUSTOMREQUEST => 'PUT'];

        return $this->execute($this->getFullUrl($path), $data, $opts, $params);
    }

    protected function patch(string $path, array $data, HttpParamCollection $params = new HttpParamCollection()): HttpResponse
    {
        $opts = [CURLOPT_CUSTOMREQUEST => 'PATCH'];

        return $this->execute($this->getFullUrl($path), $data, $opts, $params);
    }

    protected function delete(string $path, HttpParamCollection $params = new HttpParamCollection()): HttpResponse
    {
        $opts = [CURLOPT_CUSTOMREQUEST => 'DELETE'];

        return $this->execute($this->getFullUrl($path), [], $opts, $params);
    }

    protected function options(string $path, HttpParamCollection $params = new HttpParamCollection()): HttpResponse
    {
        $opts = [CURLOPT_CUSTOMREQUEST => 'OPTIONS'];

        return $this->execute($this->getFullUrl($path), [], $opts, $params);
    }

    private function execute(string $url, array $data = [], array $opts = [], HttpParamCollection $params = new HttpParamCollection()): HttpResponse
    {
        $curlC = curl_init();

        $opts[CURLOPT_HTTPHEADER] = $this->getDefaultHeaders();

        curl_setopt_array($curlC, $opts);

        if (!empty($params)) $url .= ('?' . Formatter::formatHttpParams($params));

        if (!empty($data)) {
            $data = ($this->isUpload) ? Formatter::convertToFormData($data) :json_encode($data);
            curl_setopt($curlC, CURLOPT_POSTFIELDS, $data);
        };

        curl_setopt($curlC, CURLOPT_URL, $url);
        curl_setopt($curlC, CURLOPT_RETURNTRANSFER, true);

        $headers = [];

        curl_setopt($curlC, CURLOPT_HEADERFUNCTION, function ($curl, $headerLine) use (&$headers) {
            $len = strlen($headerLine);
            $header = explode(':', $headerLine, 2);

            if (count($header) < 2) return $len;

            $key = trim($header[0]);
            $value = trim($header[1]);

            $headers[$key] = $value;

            return $len;
        });

        $response = curl_exec($curlC);
        $details = $this->activeDebug ? curl_getinfo($curlC) : null;

        curl_close($curlC);

        $response = new HttpResponse($response, curl_getinfo($curlC, CURLINFO_HTTP_CODE), $headers, $details);

        return $response;
    }
}