<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Image;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class GetImageHandler extends Handler {
    public bool $is_bufferize = false;
    
    public function handle(Request $req): void
    {
        $user_id = $req->extracts["user_id"];
        $image_id = $req->extracts["image_id"];

        $i = Image::getById($image_id);

        $connected_user = (new SessionVariable())->getUserId() ?? -1;
        
        if (!$i->is_public && $connected_user != $i->owner->id) {
            $this->response = new Response(404, "This image doesn't exists");
            return;
        }
        
        $this->response = new Response(200, $i->getBytes(), ["Content-Type" => "image/png"]);
    }
    
}
