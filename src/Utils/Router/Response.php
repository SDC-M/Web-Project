<?php

namespace Kuva\Utils\Router;

class Response
{
    public function __construct(
        public readonly int $status = 200,
        public string $body = '',
        public readonly array $headers = []
    ) {
    }
}
