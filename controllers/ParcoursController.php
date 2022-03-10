<?php
declare(strict_types=1);

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;


class ParcoursController extends Controller
{
    public function index(): void
    {
        $endpoint = $this->endpoint;
        echo $this->twig->render('pages/trails.html.twig', ['page' => $endpoint]);
    }
}
