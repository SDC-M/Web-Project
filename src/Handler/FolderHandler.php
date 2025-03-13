<?php

namespace Kuva\Handler;

use Exception;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class FolderHandler extends Handler
{
    public bool $is_bufferize = false;

    public function __construct(
        public readonly string $folder,
    ) {
    }

    private function getExtensionOfFile(string $path): string
    {
        $ext = explode('.', $path);

        return $ext[count($ext) - 1];
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
                $ext = new \FileEye\MimeMap\Extension($this->getExtensionOfFile($path));
                $mime_type = $ext->getDefaultType();
            } catch (Exception $e) {
                // UwU
            }
        }

        $this->response = new Response(200, file_get_contents($path), ['Content-Type' => $mime_type]);
    }
}
