<?php

namespace Kuva\Utils\Router;

class Response {
    public function __construct(public readonly int $status,
                                public readonly string $body,
                                public readonly array $headers = []) {}
}
