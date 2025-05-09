<?php

namespace Kuva\Handler;

use Exception;
use Kuva\Utils\Router\FileBody;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class FolderHandler extends Handler
{
    public function __construct(
        public readonly string $folder,
    ) {
    }

    public function handle(Request $req): void
    {
        $path = $this->folder.$req->extracts['path'];

        if (!is_file($path)) {
            $this->response = new Response(404);
            return;
        }

        $mime_type = mime_content_type($path);
        if ($mime_type == 'text/plain') {
            try {
                $ext = new \FileEye\MimeMap\Extension(pathinfo($path, PATHINFO_EXTENSION));
                $mime_type = $ext->getDefaultType();
            } catch (Exception $e) {
            }
        }

        $this->response = new Response(200, new FileBody($path), ['Content-Type' => $mime_type]);
    }
}
