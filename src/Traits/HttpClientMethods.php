<?php

namespace Fuganholi\MercosIntegration\Traits;

use Fuganholi\MercosIntegration\Dto\HttpParamCollection;
use Fuganholi\MercosIntegration\Dto\HttpResponse;

trait HttpClientMethods
{
    abstract public function setActiveDebug(bool $activeDebug): void;
    abstract protected function setIsUpload(bool $isUpload): void;
    abstract protected function getDefaultHeaders(): array;
    abstract protected function get(string $path, HttpParamCollection $params = new HttpParamCollection()): HttpResponse;
    abstract protected function post(string $path, array $data, HttpParamCollection $params = new HttpParamCollection()): HttpResponse;
    abstract protected function put(string $path, array $data, HttpParamCollection $params = new HttpParamCollection()): HttpResponse;
    abstract protected function patch(string $path, array $data, HttpParamCollection $params = new HttpParamCollection()): HttpResponse;
    abstract protected function delete(string $path, HttpParamCollection $params = new HttpParamCollection()): HttpResponse;
    abstract protected function options(string $path, HttpParamCollection $params = new HttpParamCollection()): HttpResponse;
}