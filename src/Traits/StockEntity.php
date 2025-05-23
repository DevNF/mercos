<?php

namespace Fuganholi\MercosIntegration\Traits;

use Fuganholi\MercosIntegration\Dto\Product\Stock\Estoque;
use Fuganholi\MercosIntegration\Dto\Product\Stock\LoteEstoque;
use Fuganholi\MercosIntegration\Exceptions\AdjustStockException;
use Fuganholi\MercosIntegration\Exceptions\BatchAdjustStockException;
use Fuganholi\MercosIntegration\Helpers\Thrower;

trait StockEntity
{
    use HttpClientMethods;

    const STOCK_ENTITY = '/v1/ajustar_estoque';
    const BATCH_STOCK_ENTITY = '/v1/ajustar_estoque_em_lote';

    public function adjustStock(Estoque $data): void
    {
        $data->validate();

        $response = $this->put(self::STOCK_ENTITY, $data->toArray());

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(AdjustStockException::class, 'An error occured when trying to adjust stock of the product!');
        }
    }

    public function batchAdjustStock(LoteEstoque $data): void
    {
        $data->validate();

        $data = $data->toArray();

        $response = $this->put(self::BATCH_STOCK_ENTITY, $data['estoques']);

        if (!$response->isSuccess()) {
            Thrower::withHttpResponse($response)
                ->throwException(BatchAdjustStockException::class, 'An error occured when trying to adjust stock of the product in batch!');
        }
    }
}