<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\repositories\EnigmaRepository;
use ceresia_adventure\repositories\TrailRepository;

class LoggedHomepageController extends LoggedController
{


    public function __construct()
    {
        parent::__construct();
        if ($this->isCreateur()) {
            header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/parcoursCreateur');

            exit;
        }
        if ($this->isAdmin()) {
            header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/utilisateurs');

            exit;
        }
    }

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
        $count = count($data_enigma);
        for ( $i=0; $i < $counter; $i++ ) {
            $remove = array_shift($data_enigma);
        }
        echo $this->twig->render('pages/gameview.html.twig', ['data_enigma' => $data_enigma, 'count' => $count, 'counter' => $counter]);
    }

    public function victory(): void
    {
        $id = $_POST['id'];

        $trailRepository = new TrailRepository();
        $data_trails = $trailRepository->select(["trail_id"=> $id])->result_array();
        echo $this->twig->render('pages/victory_page.html.twig', ['data_trails' => $data_trails]);
    }

}
