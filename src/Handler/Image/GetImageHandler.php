<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Image;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class GetImageHandler extends Handler
{
    public bool $is_bufferize = false;

    public function handle(Request $req): void
    {
        $image_id = $req->extracts["image_id"];

        $image = Image::getById($image_id);
        $this->response = new Response(404, "This image doesn't exists");
        if ($image == null) {
            return;
        }

        $connected_user = (new SessionVariable())->getUserId() ?? -1;

        if (!$image->is_public && $connected_user != $image->owner->id) {
            return;
        }

        $this->response = new Response(200, $image->getBytes(), ["Content-Type" => "image/png"]);
    }

}
