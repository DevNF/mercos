<?php

namespace Fuganholi\MercosIntegration\Traits\Http;

use Fuganholi\MercosIntegration\Dto\HttpParam;
use Fuganholi\MercosIntegration\Dto\HttpParamCollection;
use Fuganholi\MercosIntegration\Dto\HttpResponse;

trait Paginable
{
    abstract function execute(string $path, array $data = [], array $opts = [], HttpParamCollection $params = new HttpParamCollection()): HttpResponse;

    public function fetchAllPages(HttpResponse $response, string $path, HttpParamCollection $params): HttpResponse
    {
        $result = $response->getResponse();

        if (!is_array($result) || empty($result)) return $response;

        $totalExtraRequest = $response->getHeaders('MEUSPEDIDOS_REQUISICOES_EXTRAS');

        for ($i = 1; $i <= $totalExtraRequest; $i++) {
            $lastUpdate = $response->getLastUpdate();

            $param = new HttpParam('alterado_apos', $lastUpdate);
            $params->setParam($param);

            $response = $this->execute($path, [], [], $params);

            if (!$response->isSuccess()) return $response;

            $result = array_merge($result, $response->getResponse());
        }

        return new HttpResponse(
            json_encode($result),
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getRequestDetails()
        );
    }
}