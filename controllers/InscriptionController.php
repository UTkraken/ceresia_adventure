<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;

class InscriptionController extends Controller
{
    public function index(): void
    {

        $endpoint = $this->endpoint;
        echo $this->twig->render('pages/register.html.twig', ['page' => $endpoint]);
    }
}
