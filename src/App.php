<?php

namespace Kuva;

error_reporting(E_ALL);
ini_set('display_errors', true);

use Kuva\Handler\AppHandler;
use Kuva\Handler\FileHandler;
use Kuva\Handler\FolderHandler;
use Kuva\Handler\ImageFormHandler;
use Kuva\Handler\LoginHandler;
use Kuva\Handler\RecoveryHandler;
use Kuva\Handler\RegisterHandler;
use Kuva\Utils\Router;

class App
{
    public function __construct()
    {
        $r = new Router();
        $r->get('/', new AppHandler())
            ->get('/register', new FileHandler('../frontend/register.html'))
            ->get('/login', new FileHandler('../frontend/login.html'))
            ->get('/recovery', new FileHandler('../frontend/recovery-password.html'))
            ->get('/frontend/{path:+}', new FolderHandler("../frontend/"))
            ->get("/test_image", new FileHandler('../frontend/file.html'))
            /* Post Routes */
            ->post('/login', new LoginHandler())
            ->post('/register', new RegisterHandler())
            ->post("/image/new", new ImageFormHandler())
            ->post('/recovery', new RecoveryHandler())
            ->handleCurrent();
    }
}
