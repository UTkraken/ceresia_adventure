<?php

namespace ceresia_adventure\controllers;
use ceresia_adventure\framework\Controller;
use ceresia_adventure\repositories\UserRepository;

class AccueilController extends Controller
{
    public function index(): void
    {
        echo $this->twig->render('accueil.html.twig');
    }

    public function test(): void
    {
        $userRepository = new UserRepository();
        $users = $userRepository->select()->row();
        $endpoint = $this->endpoint;
        echo $this->twig->render('accueil.html.twig', ['users' => $users, 'page' => $endpoint]);
    }
}
