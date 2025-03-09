<?php

namespace Kuva\Handler;

use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class FileHandler extends Handler
{
    public bool $is_bufferize = false;

    public function __construct(public readonly string $file_path) {}

    public function handle(Request $req): void
    {
        $this->response = new Response(200, file_get_contents($this->file_path));
    }
}
