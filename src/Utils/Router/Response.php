<?php

namespace Kuva\Utils\Router;

class Response
{
    public function __construct(
        public int $status = 200,
        public string $body = '',
        public array $headers = []
    ) {
    }
}
