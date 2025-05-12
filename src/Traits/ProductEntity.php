<?php

namespace Fuganholi\MercosIntegration\Traits;

use Fuganholi\MercosIntegration\Dto\GetParams;
use Fuganholi\MercosIntegration\Dto\Product\Produto;
use Fuganholi\MercosIntegration\Exceptions\CreateProductException;
use Fuganholi\MercosIntegration\Exceptions\GetProductsException;
use Fuganholi\MercosIntegration\Exceptions\UpdateProductException;
use Fuganholi\MercosIntegration\Helpers\Thrower;

trait ProductEntity
{
    use HttpClientMethods;

    const PRODUCT_ENTITY = '/v1/produtos';

    /**
     * @return Produto[]
     */
    public function getProducts(GetParams $params = new GetParams())
    {
        $response = $this->get(self::PRODUCT_ENTITY, $params->all());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetProductsException::class, 'An error occured when trying to get all products!');
        }

        return Produto::createAllFromResponse($response);
    }

    public function getProduct(int $productId): Produto
    {
        $response = $this->get(self::PRODUCT_ENTITY . "/$productId");

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetProductsException::class, 'An error occured when trying to get the product!');
        }

        return Produto::createFromResponse($response);
    }

    public function createProduct(Produto $data): int|null
    {
        $data->validate();

        $response = $this->post(self::PRODUCT_ENTITY, $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(CreateProductException::class, 'An error occured when trying to create the product!');
        }

        return $response->getHeaders('meuspedidosid');
    }

    public function updateProduct(int $productId, Produto $data): void
    {
        $data->validate();

        $response = $this->put(self::PRODUCT_ENTITY . "/$productId", $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(UpdateProductException::class, 'An error occured when trying to update the product!');
        }
    }
}