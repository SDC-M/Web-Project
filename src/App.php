<?php

namespace Kuva;

error_reporting(E_ALL);
ini_set('display_errors', true);

use Kuva\Handler\Annotation\AnnotationFormHandler;
use Kuva\Handler\Annotation\GetAnnotationHandler;
use Kuva\Handler\AppHandler;
use Kuva\Handler\DeleteImageHandler;
use Kuva\Handler\DisconnectHandler;
use Kuva\Handler\FileHandler;
use Kuva\Handler\FolderHandler;
use Kuva\Handler\ImageFormHandler;
use Kuva\Handler\Image\GetByHandler as ImagesGet;
use Kuva\Handler\Image\GetImageHandler;
use Kuva\Handler\Image\ListUserImageHandler;
use Kuva\Handler\LoginHandler;
use Kuva\Handler\RecoveryHandler;
use Kuva\Handler\RegisterHandler;
use Kuva\Handler\UserIdHandler;
use Kuva\Handler\User\GetUserHandler;
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
            ->get('/recovery', new FileHandler('../frontend/recovery-password.html'))
            ->get('/frontend/{path:+}', new FolderHandler('../frontend/'))
            ->get('/test_image', new FileHandler('../frontend/file.html'))
            ->get('/upload-file', new FileHandler('../frontend/upload-file.html'))
            ->get('/images/{image_id}', new GetImageHandler())
            ->delete('/images/{image_id}', new DeleteImageHandler())
            ->get('/images', new ListUserImageHandler())
            ->post('/images', new ImageFormHandler())
            ->get('/user', new UserIdHandler())
            ->post('/user', new RegisterHandler())
            ->get('/user/disconnect', new DisconnectHandler())
            ->post('/user/login', new LoginHandler())
            ->get('/user/{id}', new GetUserHandler())
            ->get('/user/{id}/images', new ImagesGet())
            ->post('/user/recovery', new RecoveryHandler())
            ->get('/annotations/{user_id}/{image_id}', new FileHandler('../frontend/image.html'))
            ->get('/new_annotations/{user_id}/{image_id}', new FileHandler('../frontend/annotations.html'))
            ->get('/annotation/{image_id}', new GetAnnotationHandler())
            ->post('/annotation/{image_id}', new AnnotationFormHandler())
            ->handleCurrent();
    }
}
