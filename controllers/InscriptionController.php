<?php

//namespace ceresia_adventures\controller;

//use ceresia_adventure\framework\Controller

//TODO add 'use' for imports once namespaces are implemented
class InscriptionController extends \Controller
{
    public function index(): void
    {

        $endpoint = $this->endpoint;
        echo $this->twig->render('pages/register.html.twig', ['page' => $endpoint]);
    }
}
