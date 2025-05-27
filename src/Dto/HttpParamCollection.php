<?php

namespace Fuganholi\MercosIntegration\Dto;

use InvalidArgumentException;

final class HttpParamCollection
{
    /** @var HttpParam[] */
    private array $params;

    /**
     * @param HttpParam[] $params
     */
    public function __construct(array $params = []) {
        foreach ($params as $param) {
            if (!$param instanceof HttpParam)
                throw new InvalidArgumentException("All of element should be a HttpParam Object");
        }

        $this->params = $params;
    }

    /**
     * @return HttpParam[]
     */
    public function all(): array
    {
        return $this->params;
    }

    public function setParam(HttpParam $newParam)
    {
        foreach ($this->params as $key => $param) {
            if ($param->name == $newParam->name) {
                $this->params[$key] = $newParam;
                return;
            }
        }

        $this->params[] = $newParam;
    }
}