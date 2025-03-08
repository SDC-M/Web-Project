<?php

namespace Kuva\Utils\Router;

class Path {
    public function __construct(public string $method,
                                public string $path){}

    public function isEqual(string $path): bool {
        return $this->path == $path;
    }

    public function resolve(string $path): bool {
        return $this->path == $path;
    }    
}
