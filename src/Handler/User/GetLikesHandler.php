<?php

namespace Kuva\Handler\User;

use Kuva\Backend\Likes;
use Kuva\Backend\Middleware\UserMiddleware;
use Kuva\Utils\Router\Handler;
use Kuva\Utils\Router\JsonResponse;
use Kuva\Utils\Router\Request;

class GetLikesHandler extends Handler {
    public function handle(Request $req): void
    {
        $user = UserMiddleware::getFromSession();
        $target_user = UserMiddleware::getFromUrl($req);

        $likes = Likes::getLikesOfImagesOf($target_user);
        $total_likes = array_reduce($likes, fn ($acc, $d) => $acc + $d['count'], 0);
        $this->response = new JsonResponse(200, ["nb_likes" => $total_likes, "image" => $likes]);
    }   
}
