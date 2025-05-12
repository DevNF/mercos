<?php

namespace Fuganholi\MercosIntegration\Traits;

use Fuganholi\MercosIntegration\Dto\Customer\Cliente;
use Fuganholi\MercosIntegration\Dto\GetParams;
use Fuganholi\MercosIntegration\Exceptions\CreateCustomerException;
use Fuganholi\MercosIntegration\Exceptions\GetCustomersException;
use Fuganholi\MercosIntegration\Exceptions\UpdateCustomerException;
use Fuganholi\MercosIntegration\Helpers\Thrower;

trait CustomerEntity
{
    use HttpClientMethods;

    const CUSTOMER_ENTITY = '/v1/clientes';

    /**
     * @return Cliente[]
     */
    public function getCustomers(GetParams $params = new GetParams())
    {
        $response = $this->get(self::CUSTOMER_ENTITY, $params->all());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetCustomersException::class, 'An error occured when trying to get all customers!');
        }

        return Cliente::createAllFromResponse($response);
    }

    public function getCustomer(int $customerId): Cliente
    {
        $response = $this->get(self::CUSTOMER_ENTITY . "/$customerId");

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetCustomersException::class, 'An error occured when trying to get the customer!');
        }

        return Cliente::createFromResponse($response);
    }

    public function createCustomer(Cliente $data): int|null
    {
        $data->validate();

        $response = $this->post(self::CUSTOMER_ENTITY, $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(CreateCustomerException::class, 'An error occured when trying to create the customer!');
        }

        return $response->getHeaders('meuspedidosid');
    }

    public function updateCustomer(int $customerId, Cliente $data): void
    {
        $data->validate();

        $response = $this->put(self::CUSTOMER_ENTITY . "/$customerId", $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(UpdateCustomerException::class, 'An error occured when trying to update the customer!');
        }
    }
}