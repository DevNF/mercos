<?php

namespace Fuganholi\MercosIntegration\Traits;

use Fuganholi\MercosIntegration\Dto\Category\Categoria;
use Fuganholi\MercosIntegration\Dto\GetParams;
use Fuganholi\MercosIntegration\Exceptions\CreateCategoryException;
use Fuganholi\MercosIntegration\Exceptions\GetCategoriesException;
use Fuganholi\MercosIntegration\Exceptions\UpdateCategoryException;
use Fuganholi\MercosIntegration\Helpers\Thrower;

trait CategoryEntity
{
    use HttpClientMethods;

    const CATEGORY_ENTITY = '/v1/categorias';

    /**
     * @return Categoria[]
     */
    public function getCategories(GetParams $params = new GetParams())
    {
        $response = $this->get(self::CATEGORY_ENTITY, $params->all());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetCategoriesException::class, 'An error occured when trying to get all categories!');
        }

        return Categoria::createAllFromResponse($response);
    }

    public function getCategory(int $categoryId): Categoria
    {
        $response = $this->get(self::CATEGORY_ENTITY . "/$categoryId");

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(GetCategoriesException::class, 'An error occured when trying to get the category!');
        }

        return Categoria::createFromResponse($response);
    }

    public function createCategory(Categoria $data): int|null
    {
        $data->validate();

        $response = $this->post(self::CATEGORY_ENTITY, $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(CreateCategoryException::class, 'An error occured when trying to create the category!');
        }

        return $response->getHeaders('meuspedidosid');
    }

    public function updateCategory(int $categoryId, Categoria $data): void
    {
        $data->validate();

        $response = $this->put(self::CATEGORY_ENTITY . "/$categoryId", $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(UpdateCategoryException::class, 'An error occured when trying to update the category!');
        }
    }

    public function removeCategory(int $categoryId): void
    {
        $response = $this->put(self::CATEGORY_ENTITY . "/$categoryId", [
            'excluido' => true
        ]);

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(UpdateCategoryException::class, 'An error occured when trying to update the category!');
        }
    }

    public function retoreCategory(int $categoryId): void
    {
        $response = $this->put(self::CATEGORY_ENTITY . "/$categoryId", [
            'excluido' => false
        ]);

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(UpdateCategoryException::class, 'An error occured when trying to update the category!');
        }
    }
}