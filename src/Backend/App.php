<?php
namespace Kuva\Backend\App;

require '../../vendor/autoload.php';

use Kuva\Backend\User;

class App {
    
}

function app() {
    User::login("eee");
}
