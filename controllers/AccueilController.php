<?php

class AccueilController extends Controller
{
    public function index(): void
    {
        echo $this->twig->render('accueil.html.twig');
    }

    public function test(): void
    {
        $testRepository = new TestRepository();
        $testRepository->insert(['test' => "autre test"]);
        $tests = $testRepository->select();
        echo $this->twig->render('accueil.html.twig', ['tests' => $tests]);
    }
}