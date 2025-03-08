<?php
namespace Kuva;

use Kuva\Backend\User;

class App {
    public function __construct() {
        $r = User::register("eee", "eee", "eee");
        echo var_dump($r);
        $user = User::login("eee", "eee");
        echo var_dump($user);
    }
}
