<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;


class LoginController extends Controller
{
    public function index(): void
    {

        echo $this->twig->render('pages/login.html.twig');
    }
}
