<?php

namespace ceresia_adventure\framework;

use ceresia_adventure\utils\Config;

abstract class Controller
{
    protected \Twig\Environment $twig;
    protected array $config;
    protected string $endpoint;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('views');

        $this->twig = new \Twig\Environment($loader);

        $this->config = (new Config())->config;

        $this->endpoint = strtolower(str_replace('Controller', '', get_class($this)));
    }
}
