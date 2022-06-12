<?php

namespace ceresia_adventure\framework;

use ceresia_adventure\models\Trail;
use ceresia_adventure\repositories\EnigmaRepository;
use ceresia_adventure\utils\Config;

abstract class Controller
{
    protected \Twig\Environment $twig;
    protected array $config;
    protected string $endpoint;
    protected string $asset;

    const _FORBIDDEN_CHARACTERS = array("@", "#", "(", ")", "*", "/", ":", "$", "*", ",", "&", '!', '`');


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

    /**
     * Verify that the pseudo only contains numbers or letters
     * @param string $pseudo
     *
     * @return false|int
     */
    protected function validatePseudo(string $pseudo): int|false
    {
        return preg_match('/^[\w.-]*$/', $pseudo);
    }

    /**
     * Verify that the mail contains a valid top domain like ".com" or ".fr"
     * @param string $email
     *
     * @return bool
     */
    protected function validateEmail(string $email):bool
    {
        $splitOnAt= explode('@', $email);
        $splitAfterAt = explode('.', $splitOnAt[1]);

        if($this->isTopLevelDefined($splitAfterAt))
        {
            return $this->isTopLevelValid($splitAfterAt);
        }
        return false;
    }

    /**
     * Verify that a '.com', '.fr' or other is defined in the mail
     * @param array $splitmail
     *
     * @return bool
     */
    protected function isTopLevelDefined(array $splitmail):bool
    {
        return (count($splitmail) > 1);
    }

    /**
     * Verify that the top level is at least 2 characters long
     * @param array $splitmail
     *
     * @return bool
     */
    protected function isTopLevelValid(array $splitmail):bool
    {
        return (strlen($splitmail[1]) > 1);
    }

    /**
     * Remove special characters from the input, then sanitize any html element present
     * @param string $text
     *
     * @return string
     */
    protected function sanitizeTextInput(string $text): string
    {
        $cleanedString = str_replace(self::_FORBIDDEN_CHARACTERS, "", $text);
        return filter_var($cleanedString,FILTER_SANITIZE_STRING);
    }

    /**
     * Return an array of all trails that have at least 1 enigma
     * @param array $trails
     *
     * @return array
     */
    protected function getTrailsWithEnigma(array $trails): array
    {
        $enigmaRepository = new EnigmaRepository();
        $trailsWithEnigma = [];
        /** @var Trail $trail */
        foreach ($trails as $trail)
        {
            if($enigmaRepository->countEnigmaByTrail($trail['trail_id']) > 0)
            {
                array_push($trailsWithEnigma, $trail);
            }
        }

        return $trailsWithEnigma;
    }

    /**
     * Verify that the date is not empty. Set it to 1990 by default if that's the case
     * @param string $date
     *
     * @return string
     */
    public function handleDate(string $date): string
    {
        if( $date == '')
        {
            return '1990-01-01';
        }

           return $date;
    }
}

