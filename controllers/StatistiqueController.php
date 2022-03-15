<?php

declare(strict_types=1);

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;
use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\utils\Constantes;

class StatistiqueController extends LoggedController
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isCreateur()) {
            http_response_code(403);
            exit;
        }

    }

    public function index(): void
    {
        $statistics_test = [
          ['Nombre de parcours créés', 5],
          ['Note moyenne de vos parcours', 0.1],
          ['Temps de jeu total sur vos parcours', '39:25'],
          ['Note moyenne de vos parcours', 0.1],
        ];

        echo $this->twig->render('pages/statistics.html.twig', ['statistics' => $statistics_test]);
    }
}
