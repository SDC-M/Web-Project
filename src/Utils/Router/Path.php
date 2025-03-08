<?php

namespace Kuva\Utils\Router;

class Path {
    public function __construct(public string $method,
                                public string $path){}
}
