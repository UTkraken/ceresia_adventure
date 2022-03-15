<?php

namespace ceresia_adventure\framework;

use ceresia_adventure\utils\Config;
use ceresia_adventure\utils\Constantes;

abstract class Controller
{
    protected \Twig\Environment $twig;
    protected array $config;
    protected string $endpoint;
    protected string $asset;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('views');

        $this->twig = new \Twig\Environment($loader);

        $this->config = (new Config())->config;

        $this->asset = 'http://' . $_SERVER['HTTP_HOST'] . '/assets';

        $assets = new \Twig\TwigFunction('assets', function (string $url) {
            return $this->asset . DIRECTORY_SEPARATOR . $url;
        });
        $this->twig->addFunction($assets);
        $class_name = str_replace('ceresia_adventure\controllers\\', '', get_class($this));
        $this->endpoint = strtolower(str_replace('Controller', '', $class_name));
        $this->twig->addGlobal('page', $this->endpoint);
        $this->twig->addGlobal('session', $_SESSION);
    }

    public function isCreateur(): bool{
       return isset($_SESSION['userInfo']) && $_SESSION['userInfo']->getUserType()->getUserTypeId !== Constantes::USER_TYPE_CREATEUR;
    }

    public function isJoueur(): bool {
        return isset($_SESSION['userInfo']) && $_SESSION['userInfo']->getUserType()->getUserTypeId !== Constantes::USER_TYPE_JOUEUR;
    }
}
