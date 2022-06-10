<?php

namespace ceresia_adventure\controllers;
use ceresia_adventure\framework\Controller;
use ceresia_adventure\repositories\TrailRepository;
use ceresia_adventure\utils\Tool;

class HomepageController extends Controller
{
    public function index(): void
    {
        $trailRepository = new TrailRepository();
//        $data_trails = $trailRepository->findAll()->result();
        $data_trails = $trailRepository->select(["visible"=> 0])->result_array();
//        var_dump($data_trails);
//        die();
        echo $this->twig->render('pages/homepage.html.twig', ['data_trails' => $data_trails]);
    }
}