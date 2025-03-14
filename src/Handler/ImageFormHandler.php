<?php

namespace Kuva\Handler;

use Kuva\Backend\Image;
use Kuva\Backend\User;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;
use Kuva\Utils\SessionVariable;

class ImageFormHandler extends Handler
{
    public function handle(Request $req): void
    {
        // Set by default, error response
        $this->response = new Response(400, headers: ["Location" => "/"]);

        if (!isset($_FILES["image"]) || !isset($_POST["description"])) {
            return;
        }

        $user_id = new SessionVariable()->getUserId() ?? -1;
        $user = User::getById($user_id);

        if ($user == null) {
            return;
        }

        $image = Image::fromFile($_FILES["image"]["tmp_name"]);
        $image->description = $_POST["description"];
        if (isset($_POST['is_public']) && $_POST['is_public'] === '1') {
            $image->is_public = true;
        } else {
            $image->is_public = false;
        }
        $image->linkTo($user);
        $image->commit();

        $this->response = new Response(200, headers: ["Location" => "/profile"]);
    }
}
