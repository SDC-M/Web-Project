<?php

namespace Kuva\Handler;

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
            $media_types = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'zip' => 'application/zip',
                'mp3' => 'audio/mpeg',
                'mp4' => 'video/mp4',
                'css' => 'text/css',
                'js' => 'application/javascript',
            ];

            $ext = $this->getExtensionOfFile($path);
            if (array_key_exists($ext, $media_types)) {
                $mime_type = $media_types[$ext];
            }
        }

        $this->response = new Response(200, file_get_contents($path), ['Content-Type' => $mime_type]);
    }
}
