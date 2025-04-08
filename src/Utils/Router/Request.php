<?php

namespace Kuva\Utils\Router;

class Request
{
    /**
     * @param array<string,string> $headers
     * @param array<string,string> $extracts
     */
    public function __construct(
        public readonly array $headers,
        public readonly string $method,
        public readonly string $uri,
        public readonly string $path,
        public readonly string $body,
        public array $extracts = [],
        public array $uri_queries = [],
    ) {
    }

    private static function getHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $header => $value) {
            if (str_starts_with($header, 'HTTP_')) {
                $headers[substr($header, 5)] = $value;
            }
        }

        return $headers;
    }

    public static function fromCurrentRequest(): static
    {
        $urlquery = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $queries = [];

        parse_str($urlquery ?? '', $queries);
        return new self(
            self::getHeaders(),
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            file_get_contents('php://input'),
            [],
            $queries
        );
    }
}
