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

        //Get the current class used by the route
        $class_name = str_replace('ceresia_adventure\controllers\\', '', get_class($this));

        //Get the url endpoint by removing the Controller part of the string (enigma, utilisateurs...)
        $this->endpoint = strtolower(str_replace('Controller', '', $class_name));

        //Store the endpoint in a global variable, to be used in the sidebar to know which page we're visiting
        $this->twig->addGlobal('page', $this->endpoint);
        $this->twig->addGlobal('session', $_SESSION);
    }

    public function isLogged(): bool
    {
        return isset($_SESSION['userInfo']);
    }
}

