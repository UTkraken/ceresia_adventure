<?php

namespace ceresia_adventure\framework;

use ceresia_adventure\utils\Config;

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

        $assets = new \Twig\TwigFunction('assets', function (String $url) {
            return $this->asset . DIRECTORY_SEPARATOR . $url;
        });
        $this->twig->addFunction($assets);

        $this->endpoint = strtolower(str_replace('Controller', '', get_class($this)));
    }
}
