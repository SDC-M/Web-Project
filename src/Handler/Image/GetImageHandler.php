<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Image;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class GetImageHandler extends Handler {
    public bool $is_bufferize = false;
    
    public function handle(Request $req): void
    {
        $user_id = $req->extracts["user_id"];
        $image_id = $req->extracts["image_id"];

        $i = Image::getById($image_id);

        $this->response = new Response(200, $i->getBytes(), ["Content-Type" => "image/png"]);
    }
    
}
