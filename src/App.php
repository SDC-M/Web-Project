<?php

namespace Kuva;

error_reporting(E_ALL);
ini_set('display_errors', true);

use Kuva\Handler\FileHandler;
use Kuva\Handler\FolderHandler;
use Kuva\Handler\LoginHandler;
use Kuva\Utils\Router;
use Kuva\Handler\RegisterHandler;

class App
{
    public function __construct()
    {
        $r = new Router;
        $r->get('/', new FileHandler("../frontend/login.html"))
            ->get('/register', new FileHandler("../frontend/register.html"))
            ->get('/login', new FileHandler("../frontend/login.html"))
            ->get('/frontend/css/{file}', new FolderHandler("../frontend/css/"))
            ->get('/frontend/img/{file}', new FolderHandler("../frontend/img/"))
            ->get('/frontend/js/{file}', new FolderHandler("../frontend/js/"))
            ->post('/login', new LoginHandler)
            ->post('/register', new RegisterHandler)
            ->handleCurrent();
    }
}
