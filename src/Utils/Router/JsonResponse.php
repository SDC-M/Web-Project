<?php

namespace Kuva\Utils\Router;

class JsonResponse extends Response {
    public function __construct(
        int $status = 200,
        mixed $body = null,
        array $headers = []
    ) {
        $headers["Content-Type"] = "application/json";
        parent::__construct($status, json_encode($body), $headers);
    }
}
