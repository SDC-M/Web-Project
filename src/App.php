<?php
namespace Kuva;

use Kuva\Backend\User;

class App {
    public function __construct() {
        $user = User::login("me", "me");
        if ($user == null) {
            echo "Not found";
        }
    }
}
