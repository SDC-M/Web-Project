<?php

namespace Kuva\Handler;

use Kuva\Utils\Router\FileBody;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class FileHandler extends Handler
{
    public function __construct(public readonly string $file_path)
    {
    }

    public function handle(Request $req): void
    {
        if (!file_exists($this->file_path)) {
            $this->response = new Response(404, "Not found");
            return;
        }

        $this->response = new Response(200, new FileBody($this->file_path));
    }
}
