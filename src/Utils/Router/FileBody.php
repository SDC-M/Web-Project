<?php

namespace Kuva\Utils\Router;

class FileBody
{
    public function __construct(public string $path)
    {
    }

    public function outputToBody(): void
    {
        readfile($this->path);
    }
}
