<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;
use ceresia_adventure\models\User;
use ceresia_adventure\repositories\UserRepository;

class InscriptionController extends Controller
{
    public function index(): void
    {
        echo $this->twig->render('pages/register.html.twig');
    }

    public function addUser(): void
    {
        $userRepository = new UserRepository();
        $error = $this->_insertControl();

        if (empty($error)) {
            $userId = $userRepository->insert(
                [
                    'pseudo' => $_REQUEST['pseudo'],
                    'email' => $_REQUEST['email'],
                    'password' => password_hash($_REQUEST['password'],PASSWORD_DEFAULT),
                    'user_type_id' => $_REQUEST['user_type_id'],
                    'departement' => $_REQUEST['departement']
                ]
            );
            $user = $userRepository->findById($userId)->row();
            $_SESSION['userInfo'] = $user;
            header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/accueil');
        } else {
            echo $this->twig->render('pages/register.html.twig', ['errors'=>$error]);
        }
    }

    private function _insertControl(): array
    {
        $userRepository = new UserRepository();
        $userVerif = $userRepository->select(['email' => $_REQUEST['email']])->row();
        $errors = [];
        if ($_REQUEST['password'] != $_REQUEST['password_confirm']) {
            $errors[] = 'Les mots de passe sont différents';
        }
        if ($userVerif != null) {
            $errors[] = 'l\'adresse email existe déjà';
        }
        return $errors;
    }
}
