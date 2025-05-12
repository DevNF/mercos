<?php

namespace Fuganholi\MercosIntegration\Dto;

use DateTime;

class GetParams
{
    public function __construct(
        public ?DateTime $updatedAfter = null
    ) {
        //
    }

    public function all(): HttpParamCollection
    {
        $params = [];

        if ($this->updatedAfter) $params[] = new HttpParam('alterado_apos', $this->updatedAfter->format('Y-m-d H:i:s'));

        return new HttpParamCollection($params);
    }
}