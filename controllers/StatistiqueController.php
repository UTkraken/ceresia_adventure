<?php

declare(strict_types=1);

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;

class StatistiqueController extends Controller
{
    public function index(): void
    {

        $statistics_test = [
          ['Nombre de parcours créés', 5],
          ['Note moyenne de vos parcours', 0.1],
          ['Temps de jeu total sur vos parcours', '39:25'],
          ['Note moyenne de vos parcours', 0.1],
        ];

        $endpoint = $this->endpoint;
        echo $this->twig->render('pages/statistics.html.twig', ['page' => $endpoint, 'statistics' => $statistics_test]);
    }
}
