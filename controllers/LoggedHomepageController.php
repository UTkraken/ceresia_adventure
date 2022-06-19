<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\models\Rating;
use ceresia_adventure\models\Trail;
use ceresia_adventure\repositories\EnigmaRepository;
use ceresia_adventure\repositories\RatingRepository;
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
        $data_trails = $trailRepository->select(["visible"=> 1])->result_array();
        $trails = $this->getTrailsWithEnigma($data_trails);

        echo $this->twig->render('pages/logged_homepage.html.twig', ['data_trails' => $trails]);
    }

    public function play(): void
    {
        $id = $_POST['id'];
        $counter = $_POST['counter'];

        $enigmaRepository = new EnigmaRepository();
        $ratingRepository = new RatingRepository();
        $data_enigma = $enigmaRepository->select(['trail_id'=>$id])->result_array();

        $userRating = $ratingRepository->select(['user_id' => $this->user->getUserId(), 'trail_id' => $id])->row();
        if($userRating)
        {
            echo "<div class='list_container'><h3> Parcours déjà accompli </h3></div>";
            echo "<a href='/loggedhomepage'> Retourner à l'accueil </a>";

        } else
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

    public function addRating() :void
    {
        $userId = $this->user->getUserId();
        $trailId = $_POST['id'];
        $ratingRepository = new RatingRepository();
         $ratingRepository->insert([
            'rating' => $_REQUEST['rating'],
            'trail_id' => intval($trailId),
            'user_id' => $userId
        ]);
    }

}
