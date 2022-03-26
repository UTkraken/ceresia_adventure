<?php

namespace ceresia_adventure\controllers;
use ceresia_adventure\framework\Controller;
use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\repositories\UserRepository;

class AccueilController extends LoggedController
{
    public function index(): void
    {
        echo $this->twig->render('accueil.html.twig');
    }

    public function test(): void
    {
        $userRepository = new UserRepository();
        $users = $userRepository->select()->row();

        echo $this->twig->render('accueil.html.twig', ['users' => $users]);
    }
}
