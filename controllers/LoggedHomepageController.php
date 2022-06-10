<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\repositories\EnigmaRepository;
use ceresia_adventure\repositories\TrailRepository;

class LoggedHomepageController extends LoggedController
{
    public function index(): void
    {
        $trailRepository = new TrailRepository();
        $data_trails = $trailRepository->select(["visible"=> 0])->result_array();
        echo $this->twig->render('pages/logged_homepage.html.twig', ['data_trails' => $data_trails]);
    }

    public function play(): void
    {
        $id = $_POST['id'];
        $counter = $_POST['counter'];

        $enigmaRepository = new EnigmaRepository();
        $data_enigma = $enigmaRepository->select(['trail_id'=>$id])->result_array();
        echo $this->twig->render('pages/gameview.html.twig', ['data_enigma' => $data_enigma, 'count' => count($data_enigma), 'counter' => $counter]);
    }

    public function nextEnigma(): void
    {
        $id = $_POST['id'];
        $counter = $_POST['counter'];

        $enigmaRepository = new EnigmaRepository();
        $data_enigma = $enigmaRepository->select(['trail_id'=>$id])->result_array();
        echo $this->twig->render('pages/gameview.html.twig', ['data_enigma' => $data_enigma, 'count' => count($data_enigma), 'counter' => $counter]);
    }

}