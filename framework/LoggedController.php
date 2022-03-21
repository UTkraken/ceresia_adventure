<?php

namespace ceresia_adventure\framework;

use ceresia_adventure\models\User;
use ceresia_adventure\utils\Config;
use ceresia_adventure\utils\Constantes;

abstract class LoggedController extends Controller
{
    protected User $user;

    public function __construct()
    {
        parent::__construct();
        if(!$this->isLogged()) {
            header('Location: ' .  'http://' . $_SERVER['HTTP_HOST'] . '/login');
        }
        $this->user = $_SESSION['userInfo'];
    }

    public function isLogged(): bool{
        return isset($_SESSION['userInfo']);
    }
}
