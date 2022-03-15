<?php

namespace ceresia_adventure\framework;

use ceresia_adventure\utils\Config;
use ceresia_adventure\utils\Constantes;

abstract class LoggedController extends Controller
{
    protected \Twig\Environment $twig;
    protected array $config;
    protected string $endpoint;
    protected string $asset;

    public function __construct()
    {
        parent::__construct();
        if(!$this->isLogged()) {
            header('Location: ' .  'http://' . $_SERVER['HTTP_HOST'] . '/login');
        }
    }

    public function isLogged(): bool{
        return isset($_SESSION['userInfo']);
    }
}
