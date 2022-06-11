<?php

namespace ceresia_adventure\controllers;
use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\repositories\UserRepository;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class ProfileController
 * @package ceresia_adventure\controllers
 *
 */
class ProfileController extends LoggedController
{

    public function index(): void
    {
        $userRepository = new UserRepository();

        $user = $this->user;
        $userFromDb = $userRepository->select(['user_id' => $user->getUserId()])->result();

        echo $this->twig->render('pages/profile.html.twig', ['user_data' => $userFromDb[0]]);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editUser(): void
    {
        $userRepository = new UserRepository();
        $userId = $_POST['user_id'];
        $result = $userRepository->select(['user_id' => $userId])->result();

        echo $this->twig->render('pages/edit_profile.html.twig', ['user' => $result, 'user_id' => $_POST['user_id']]);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function insertUser(): void
    {
        $userRepository = new UserRepository();
        $userId = $_POST['user_id'];
        if (empty($error)) {
            $userRepository->update(
                [
                    'pseudo' => $_REQUEST['pseudo'],
                    'email' => $_REQUEST['email'],
                    'departement' => $_REQUEST['department'],
                ],
                ['user_id' => $_REQUEST['user_id']]
            );
            header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/profile');
        } else {
            echo '<div class="something">' . $error[0];
        }

        $result = $userRepository->select(['user_id' => $userId])->result();
        echo $this->twig->render('pages/edit_profile.html.twig', ['user' => $result, 'user_id' =>$userId]);
    }
}
