<?php
namespace Kuva;

use Kuva\Backend\User;

class App {
    public function __construct()
    {       
        echo var_dump(User::login("me", "me"));
    }
}
