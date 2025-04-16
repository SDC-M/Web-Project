<?php

namespace Kuva\Handler\Image;

use Kuva\Backend\Categories;
use Kuva\Backend\Image;
use Kuva\Backend\Logs;
use Kuva\Backend\Middleware\FormMiddleware;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\FormValidator;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\Request;
use Kuva\Utils\Router\Response;

class ImageFormHandler extends Handler
{
    public function handle(Request $req): void
    {
        $form = FormMiddleware::validate((new FormValidator())
            ->addFileFieldWithAcceptedMimeType("image", ["image/png", "image/jpeg", "image/webp", "image/gif"])
            ->addOptionalTextField("description")
            ->addCheckBoxField("is_public"));

        $user = UserMiddleware::getFromSession();

        if ($form["image"]["error"] != 0) {
            $this->response = new Response(413, "Problem when handling image (Probably too large)");
            return;
        }


        $path = escapeshellarg($form["image"]["tmp_name"]);
        $result = '';
        $status = 0;
        $cmd = "ffmpeg -i {$path} -vcodec webp -loop 0 -pix_fmt yuva420p {$path}.webp";
        $ffmpeg_cmd = exec($cmd, $result, $status);
        if ($status == 1) {
            $this->response = new Response(500);
            return;
        }

        $image = Image::fromFile($form["image"]["tmp_name"] . ".webp");
        $image->description = $form["description"];
        $image->is_public = $form["is_public"];
        $image->linkTo($user);
        $image->commit();

        Categories::setFromDescription($image, $form["description"]);

        Logs::create_with("User {$user->id} create an image", $user);

        $this->response = new Response(200);
    }
}
