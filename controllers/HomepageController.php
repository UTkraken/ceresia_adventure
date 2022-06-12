<?php

namespace ceresia_adventure\controllers;
use ceresia_adventure\framework\Controller;
use ceresia_adventure\models\Trail;
use ceresia_adventure\repositories\EnigmaRepository;
use ceresia_adventure\repositories\TrailRepository;

class HomepageController extends Controller
{
    public function index(): void
    {
        $trailRepository = new TrailRepository();

        $data_trails = $trailRepository->select(["visible"=> 1])->result_array();
        $trails = $this->getTrailsWithEnigma($data_trails);

        echo $this->twig->render('pages/homepage.html.twig', ['data_trails' => $trails]);
    }
}
