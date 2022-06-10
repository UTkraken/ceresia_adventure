<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\repositories\TrailRepository;

class LoggedHomepageController extends LoggedController
{
    public function index(): void
    {
        $trailRepository = new TrailRepository();
        $data_trails = $trailRepository->select(["visible"=> 0])->result_array();
        echo $this->twig->render('pages/logged_homepage.html.twig', ['data_trails' => $data_trails]);
    }

}