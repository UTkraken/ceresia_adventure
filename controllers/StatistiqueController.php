<?php
declare(strict_types=1);


class StatistiqueController extends Controller
{
    public function index(): void
    {
        $endpoint = $this->endpoint;
        echo $this->twig->render('pages/statistics.html.twig', ['page' => $endpoint]);
    }
}
