<?php
declare(strict_types=1);


class LoginController extends Controller
{
    public function index(): void
    {
        $endpoint = $this->endpoint;
        echo $this->twig->render('pages/login.html.twig', ['page' => $endpoint]);
    }
}
