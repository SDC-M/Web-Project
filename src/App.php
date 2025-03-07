<?php
namespace Kuva;

use Kuva\Backend\User;

require '../vendor/autoload.php';

class App {
    public function __construct()
    {

        
         User::login("eee");
    }

}
