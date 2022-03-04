<?php

class AccueilController extends Controller
{
    public function index(): void
    {
        echo $this->twig->render('accueil.html.twig');
    }

    public function test(): void
    {
        $userRepository = new UserRepository();
        $users = $userRepository->select();
        $endpoint = $this->endpoint;
        echo $this->twig->render('accueil.html.twig', ['users' => $users, 'page' => $endpoint]);
    }
}
