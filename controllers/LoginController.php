<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;
use ceresia_adventure\repositories\UserRepository;


class LoginController extends Controller
{
    public function index(): void
    {

        echo $this->twig->render('pages/login.html.twig');
    }

    public function connexionForm(): void
    {
        $userRepository = new UserRepository();
        $user = $userRepository->select(['email'=>$_REQUEST['email'],
                                'password'=>$_REQUEST['password']])->row();
        if ( isset($user) ) {
            $_SESSION['userInfo'] = $user;
            header('Location: ' .  'http://' . $_SERVER['HTTP_HOST'] . '/parcours');
        } else {
            echo $this->twig->render('pages/login.html.twig', ['errorMessage'=>'Votre identifiant ou votre mot de passe est incorret !']);
        }
    }
}
