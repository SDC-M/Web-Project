<?php

namespace Kuva;

error_reporting(E_ALL);
ini_set('display_errors', true);

use Kuva\Handler\Annotation\AnnotationFormHandler;
use Kuva\Handler\Annotation\DeleteAnnotationHandler;
use Kuva\Handler\Annotation\GetAnnotationHandler;
use Kuva\Handler\AppHandler;
use Kuva\Handler\FileHandler;
use Kuva\Handler\FolderHandler;
use Kuva\Handler\Image\DeleteImageHandler;
use Kuva\Handler\Image\GetDetailsHandler;
use Kuva\Handler\Image\ImageFormHandler;
use Kuva\Handler\Image\GetByHandler as ImagesGet;
use Kuva\Handler\Image\GetDescriptionOfImageHandler;
use Kuva\Handler\Image\GetImageHandler;
use Kuva\Handler\Image\ListUserImageHandler;
use Kuva\Handler\Image\PutPermissionHandler;
use Kuva\Handler\User\DisconnectHandler;
use Kuva\Handler\User\LoginHandler;
use Kuva\Handler\User\RecoveryHandler;
use Kuva\Handler\User\RegisterHandler;
use Kuva\Handler\User\UserIdHandler;
use Kuva\Handler\User\GetUserHandler;
use Kuva\Handler\Feed\Get as FeedHandler;
use Kuva\Handler\User\PostBiographyHandler;
use Kuva\Handler\Image\Likes\Delete as DeleteLikeImageHandler;
use Kuva\Handler\Image\Likes\Get as GetLikeImageHandler;
use Kuva\Handler\Image\Likes\Post as PostLikeImageHandler;
use Kuva\Utils\Router;

class App
{
    public function __construct()
    {
        $r = new Router();
        $r->get('/', new AppHandler())
            ->get('/register', new FileHandler('../frontend/register.html'))
            ->get('/login', new FileHandler('../frontend/login.html'))
            ->get('/profile', new FileHandler('../frontend/profile.html'))
            ->get('/profile/{user_id}', new FileHandler('../frontend/profile.html'))
            ->get('/recovery', new FileHandler('../frontend/recovery-password.html'))
            ->get('/frontend/{path:+}', new FolderHandler('../frontend/'))
            ->get('/test_image', new FileHandler('../frontend/file.html'))
            ->get('/upload-file', new FileHandler('../frontend/upload-file.html'))
            ->get('/annotations/{user_id}/{image_id}', new FileHandler('../frontend/image.html'))
            ->get('/new_annotations/{user_id}/{image_id}', new FileHandler('../frontend/annotations.html'))
            ->get('/settings', new FileHandler('../frontend/settings.html'))
            /*
                Backend routes should probably be prefix by api to make that clearer but anyways.
                If I do that I'll be fuck by Seb. So even if I want it to happen, I not ready for the moment.
                If you are not Seb, forget what is written above. :)
            */
            ->get('/images/{image_id}', new GetImageHandler())
            ->delete('/images/{image_id}', new DeleteImageHandler())
            ->put('/images/{image_id}/permission', new PutPermissionHandler())
            /* /description should be delete cause it's */
            ->get('/images/{image_id}/description', new GetDescriptionOfImageHandler())
            ->get('/images/{image_id}/details', new GetDetailsHandler())            
            ->get('/images/{image_id}/likes', new GetLikeImageHandler())
            ->post('/images/{image_id}/likes', new PostLikeImageHandler())
            ->delete('/images/{image_id}/likes', new DeleteLikeImageHandler())
            ->get('/images', new ListUserImageHandler())
            ->post('/images', new ImageFormHandler())
            ->get('/user', new UserIdHandler())
            ->post('/user', new RegisterHandler())
            ->get('/user/disconnect', new DisconnectHandler())
            ->post('/user/login', new LoginHandler())
            ->get('/user/{id}', new GetUserHandler())
            ->get('/user/{id}/images', new ImagesGet())
            ->post('/user/update', new PostBiographyHandler())
            ->post('/user/recovery', new RecoveryHandler())
            ->get('/annotation/{image_id}', new GetAnnotationHandler())
            ->post('/annotation/{image_id}', new AnnotationFormHandler())
            ->delete('/annotation/{annotation_id}', new DeleteAnnotationHandler())
            ->get("/feed", new FeedHandler())
            ->handleCurrent();
    }
}
