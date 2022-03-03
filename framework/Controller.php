<?php

abstract class Controller
{
    protected \Twig\Environment $twig;
    protected array $config;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('views');

        $this->twig = new \Twig\Environment($loader);

        $this->config = (new Config())->config;
    }
}