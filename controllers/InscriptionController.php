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

        $errors = $this->_insertControl();

        if (empty($errors)) {
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
            echo $this->twig->render('pages/register.html.twig', ['errors'=>$errors]);
        }
    }

    private function _insertControl(): array
    {
        $errors = [];
        $userRepository = new UserRepository();
        $userVerif = $userRepository->select(['email' => $_REQUEST['email']])->row();

        if ($_REQUEST['password'] != $_REQUEST['password_confirm'])
        {
            array_push($errors, 'Les mots de passe sont différents');
        }
        if ($userVerif != null)
        {
            array_push($errors, 'l\'adresse email existe déjà');
        }
        if(!$this->validateEmail($_REQUEST['email']))
        {
            array_push($errors, 'Ce mail est invalide');
        }

        if($this->validatePseudo($_REQUEST['pseudo']) == 0)
        {
            array_push($errors, 'Nom invalide (seuls les chiffres et lettres sont autorisés');
        }

        return $errors;
    }
}
