<?php

namespace Kuva\Utils\Router;

/**
    $uri if 
*/
class Request {
    public function __construct(public readonly array $headers,
        public readonly string $method,
    public readonly string $uri,
    public readonly string $body) {}

    private static function getHeaders(): array {
        $headers = [];
        foreach ($_SERVER as $header => $value) {
            if (str_starts_with($header, "HTTP_")) {
                $headers[substr($header, 5)] = $value;
            }
        }

        return $headers;
    }

    public static function fromCurrentRequest(): static {
        return new static(self::getHeaders(),
            $_SERVER["REQUEST_METHOD"],
            $_SERVER["REQUEST_URI"],
           file_get_contents('php://input'));
    }
}
