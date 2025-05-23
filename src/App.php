<?php

namespace Kuva;

use Kuva\Handler\Annotation\AnnotationFormHandler;
use Kuva\Handler\Annotation\DeleteAnnotationHandler;
use Kuva\Handler\Annotation\GetAnnotationHandler;
use Kuva\Handler\AppHandler;
use Kuva\Handler\Categories\GetCategories;
use Kuva\Handler\Categories\GetImagesOfCategories;
use Kuva\Handler\FileHandler;
use Kuva\Handler\FolderHandler;
use Kuva\Handler\Image\DeleteImageHandler;
use Kuva\Handler\Image\GetCategorieOfImageHandler;
use Kuva\Handler\Image\GetDetailsHandler;
use Kuva\Handler\Image\ImageFormHandler;
use Kuva\Handler\Image\GetByHandler as ImagesGet;
use Kuva\Handler\Image\GetImageHandler;
use Kuva\Handler\Image\ListUserImageHandler;
use Kuva\Handler\Image\PutDescriptionOfImageHandler;
use Kuva\Handler\Image\PutPermissionHandler;
use Kuva\Handler\Logs\GetLogs;
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
use Kuva\Handler\User\GetLikesHandler;
use Kuva\Handler\User\GetProfilePictureHandler;
use Kuva\Handler\User\PutProfilePictureHandler;
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
            ->get('/ad', new FileHandler('../frontend/admin-dashbord.html'))
            ->get('/api/image/{image_id}', new GetImageHandler())
            ->delete('/api/image/{image_id}', new DeleteImageHandler())
            ->put('/api/image/{image_id}/permission', new PutPermissionHandler())
            ->put('/api/image/{image_id}/description', new PutDescriptionOfImageHandler())
            ->get('/api/image/{image_id}/details', new GetDetailsHandler())
            ->get('/api/image/{image_id}/categories', new GetCategorieOfImageHandler())
            ->get('/api/image/{image_id}/likes', new GetLikeImageHandler())
            ->post('/api/image/{image_id}/likes', new PostLikeImageHandler())
            ->delete('/api/image/{image_id}/likes', new DeleteLikeImageHandler())
            ->get('/api/images', new ListUserImageHandler())
            ->post('/api/images', new ImageFormHandler())
            ->get('/api/user', new UserIdHandler())
            ->post('/api/user', new RegisterHandler())
            ->get('/api/user/disconnect', new DisconnectHandler())
            ->post('/api/user/login', new LoginHandler())
            ->get('/api/user/{user_id}/likes', new GetLikesHandler())
            ->get('/api/user/{user_id}/images', new ImagesGet())
            ->get('/api/user/{user_id}/picture', new GetProfilePictureHandler())
            ->get('/api/user/{user_id}', new GetUserHandler())
            ->put('/api/user/me/picture', new PutProfilePictureHandler())
            ->post('/api/user/update', new PostBiographyHandler())
            ->post('/api/user/recovery', new RecoveryHandler())
            ->get('/api/annotation/{image_id}', new GetAnnotationHandler())
            ->post('/api/annotation/{image_id}', new AnnotationFormHandler())
            ->delete('/api/annotation/{annotation_id}', new DeleteAnnotationHandler())
            ->get("/api/category/{category_name}", new GetCategories())
            ->get("/api/category/{category_name}/images", new GetImagesOfCategories())
            ->get('/api/feed', new FeedHandler())
            ->get('/api/logs', new GetLogs())
            ->handleCurrent();
    }
}
