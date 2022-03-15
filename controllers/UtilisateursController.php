<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;
use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\repositories\UserRepository;

class UtilisateursController extends LoggedController
{
    public function index(): void
    {
        $userRepository = new UserRepository();
        $userList = $userRepository->select()->result();
       echo $this->twig->render('pages/admin/user_list.html.twig', ['userList' => $userList]);
    }
}
