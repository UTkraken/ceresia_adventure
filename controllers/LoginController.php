<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;


class LoginController extends Controller
{
    public function index(): void
    {
        $endpoint = $this->endpoint;
        echo $this->twig->render('pages/login.html.twig', ['page' => $endpoint]);
    }
}
