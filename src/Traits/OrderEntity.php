<?php

namespace Fuganholi\MercosIntegration\Traits;

use Fuganholi\MercosIntegration\Dto\GetParams;
use Fuganholi\MercosIntegration\Dto\Order\Faturamento;
use Fuganholi\MercosIntegration\Dto\Order\Pedido;
use Fuganholi\MercosIntegration\Exceptions\CreateOrderBillingException;
use Fuganholi\MercosIntegration\Exceptions\CancelOrderException;
use Fuganholi\MercosIntegration\Exceptions\GetOrdersException;
use Fuganholi\MercosIntegration\Exceptions\UpdateOrderBillingException;
use Fuganholi\MercosIntegration\Helpers\Thrower;

trait OrderEntity
{
    use HttpClientMethods;

    const ORDER_V1_ENTITY = '/v1/pedidos';
    const ORDER_V2_ENTITY = '/v2/pedidos';
    const ORDER_BILLING_ENTITY = '/v1/faturamento';

    /**
     * @return Pedido[]
     */
    public function getOrders(GetParams $params = new GetParams())
    {
        $response = $this->get(self::ORDER_V2_ENTITY, $params->all());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetOrdersException::class, 'An error occured when trying to get all orders!');
        }

        return Pedido::createAllFromResponse($response);
    }

    public function getOrder(int $orderId): Pedido
    {
        $response = $this->get(self::ORDER_V2_ENTITY . "/$orderId");

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetOrdersException::class, 'An error occured when trying to get the order!');
        }

        return Pedido::createFromResponse($response);
    }

    public function cancelOrder(int $orderId): void
    {
        $response = $this->post(self::ORDER_V1_ENTITY . "/cancelar/$orderId", []);

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(CancelOrderException::class, 'An error occured when trying to cancel the order!');
        }
    }

    public function createOrderBilling(Faturamento $data): int|null
    {
        $data->validate();

        $response = $this->post(self::ORDER_BILLING_ENTITY, $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(CreateOrderBillingException::class, 'An error occured when trying to create order billing!');
        }

        return $response->getHeaders('meuspedidosid');
    }

    public function updateOrderBilling(int $billingId, Faturamento $data): void
    {
        $data->validate();

        $response = $this->put(self::ORDER_BILLING_ENTITY . "/$billingId", $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(UpdateOrderBillingException::class, 'An error occured when trying to update order billing!');
        }
    }
}