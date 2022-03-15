<?php
declare(strict_types=1);

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;
use ceresia_adventure\framework\LoggedController;


class ParcoursController extends LoggedController
{
    public function index(): void
    {
        echo $this->twig->render('pages/trails.html.twig');
    }

    public function addNewTrack() : void
    {
        echo $this->twig->render('pages/createur/add_new_track.html.twig');
    }
}
