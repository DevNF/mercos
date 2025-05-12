<?php

namespace Fuganholi\MercosIntegration\Traits;

use Fuganholi\MercosIntegration\Dto\Carrier\Transportadora;
use Fuganholi\MercosIntegration\Dto\GetParams;
use Fuganholi\MercosIntegration\Exceptions\CreateCarrierException;
use Fuganholi\MercosIntegration\Exceptions\GetCarriersException;
use Fuganholi\MercosIntegration\Exceptions\UpdateCarrierException;
use Fuganholi\MercosIntegration\Helpers\Thrower;

trait CarrierEntity
{
    use HttpClientMethods;

    const CARRIER_ENTITY = '/v1/transportadoras';

    /**
     * @return Transportadora[]
     */
    public function getCarriers(GetParams $params = new GetParams())
    {
        $response = $this->get(self::CARRIER_ENTITY, $params->all());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetCarriersException::class, 'An error occured when trying to get all carriers!');
        }

        return Transportadora::createAllFromResponse($response);
    }

    public function getCarrier(int $carrierId): Transportadora
    {
        $response = $this->get(self::CARRIER_ENTITY . "/$carrierId");

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetCarriersException::class, 'An error occured when trying to get the carrier!');
        }

        return Transportadora::createFromResponse($response);
    }

    public function createCarrier(Transportadora $data): int|null
    {
        $data->validate();

        $response = $this->post(self::CARRIER_ENTITY, $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(CreateCarrierException::class, 'An error occured when trying to create the carrier!');
        }

        return $response->getHeaders('meuspedidosid');
    }

    public function updateCarrier(int $carrierId, Transportadora $data): void
    {
        $data->validate();

        $response = $this->put(self::CARRIER_ENTITY . "/$carrierId", $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(UpdateCarrierException::class, 'An error occured when trying to update the carrier!');
        }
    }
}