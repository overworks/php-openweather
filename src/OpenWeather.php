<?php

namespace Minhyung\OpenWeather;

class OpenWeather
{
    public function __construct(
        private string $apiKey
    ) {
        //
    }

    public function onecall(): OneCall
    {
        return new OneCall($this->apiKey);
    }
}
