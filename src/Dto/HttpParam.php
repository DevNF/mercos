<?php

namespace Fuganholi\MercosIntegration\Dto;

class HttpParam
{
    public function __construct(
        public string  $name,
        public mixed   $value
    ) {
        //
    }
}