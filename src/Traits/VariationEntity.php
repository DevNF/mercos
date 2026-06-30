<?php

namespace Fuganholi\MercosIntegration\Traits;

use Fuganholi\MercosIntegration\Dto\GetParams;
use Fuganholi\MercosIntegration\Dto\Variation\Variacao;
use Fuganholi\MercosIntegration\Exceptions\CreateVariationException;
use Fuganholi\MercosIntegration\Exceptions\GetVariationsException;
use Fuganholi\MercosIntegration\Exceptions\UpdateVariationException;
use Fuganholi\MercosIntegration\Helpers\Thrower;

trait VariationEntity
{
    use HttpClientMethods;

    const VARIATION_ENTITY = '/v1/variacoes';

    /**
     * @return Variacao[]
     */
    public function getVariations(GetParams $params = new GetParams())
    {
        $response = $this->get(self::VARIATION_ENTITY, $params->all());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetVariationsException::class, 'An error occured when trying to get all variations!');
        }

        return Variacao::createAllFromResponse($response);
    }

    public function getVariation(int $variationId): Variacao
    {
        $response = $this->get(self::VARIATION_ENTITY . "/$variationId");

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetVariationsException::class, 'An error occured when trying to get the variation!');
        }

        return Variacao::createFromResponse($response);
    }

    /**
     * Cria uma variação e devolve o corpo da resposta (id da variação +
     * itens_variacoes na mesma ordem enviada).
     */
    public function createVariation(Variacao $data): mixed
    {
        $data->validate();

        $response = $this->post(self::VARIATION_ENTITY, $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(CreateVariationException::class, 'An error occured when trying to create the variation!');
        }

        return $response->getResponseJson();
    }

    /**
     * Atualiza uma variação e devolve o corpo da resposta (id da variação +
     * itens_variacoes na mesma ordem enviada).
     */
    public function updateVariation(int $variationId, Variacao $data): mixed
    {
        $response = $this->put(self::VARIATION_ENTITY . "/$variationId", $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(UpdateVariationException::class, 'An error occured when trying to update the variation!');
        }

        return $response->getResponseJson();
    }
}
