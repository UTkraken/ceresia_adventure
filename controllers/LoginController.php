<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;
use ceresia_adventure\models\User;
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
        /** @var User $user */
        $user = $userRepository->select(['email'=>$_REQUEST['email']])->row();

        if(!isset($user))
        {
            echo $this->twig->render('pages/login.html.twig', ['errorMessage'=>'Votre identifiant ou mot de passe est incorrect']);

            exit();
        }

        if($this->isPasswordCorrect($_REQUEST['password'], $user->getPassword()))
        {
            $_SESSION['userInfo'] = $user;
            header('Location: ' .  'http://' . $_SERVER['HTTP_HOST'] . '/loggedhomepage');
        } else {
            echo $this->twig->render('pages/login.html.twig', ['errorMessage'=>'Votre identifiant ou mot de passe est incorrect']);
        }
    }

    /** Compare the form password with the hashed password in database
     * @param string $pass
     * @param string $hash
     *
     * @return bool
     */
    private function isPasswordCorrect(string $pass, string $hash): bool
    {
        return password_verify($pass, $hash);
    }
}
