<?php

namespace Kuva\Handler\Annotation;

use Kuva\Backend\Annotation;
use Kuva\Backend\Image;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class GetAnnotationHandler extends Handler
{
    public bool $is_bufferize = false;

    public function handle(Request $req): void
    {
        $this->response = new Response(400);
        $image_id = $req->extracts["image_id"] ?? -1;
        $image = Image::getById($image_id);

        if ($image == null) {
            return;
        }

        $this->response = new Response(200, json_encode(Annotation::getAllOfImage($image)), ["Content-Type" => "application/json"]);
    }
}
