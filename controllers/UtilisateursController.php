<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;
use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\repositories\TrailRepository;
use ceresia_adventure\repositories\UserRepository;
use ceresia_adventure\utils\Tool;

class UtilisateursController extends LoggedController
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->isAdmin()) {
            http_response_code(403);
            exit;
        }
    }

    public function index(): void
    {
        echo $this->twig->render('pages/admin/user_list.html.twig', ['userList' => $this->_get4gridUsers(null)]);
    }

    public function remove(): void
    {
        $id = $_POST['id'];

        $userRepository = new UserRepository();
        echo $userRepository->update(['supprime' => 1], ['user_id' => $id]);
    }

    private function _get4gridUsers(?string $pseudo): string
    {
        $userRepository = new UserRepository();
        $result = $userRepository->select(['pseudo' => '%' . $pseudo . '%', 'supprime' => 0])->result();

        $users = [];
        foreach ($result as $row) {
            $user = [
                'user_id' => $row->getUserId(),
                'pseudo' => $row->getPseudo(),
                'email' => $row->getEmail(),
            ];
            $user['actions'] = Tool::addBtnDataTable('edit', 'fa-pencil', 'edit', 'Modifier l\'utilisateur', ['id' => $row->getUserId()]);
            $user['actions'] .= Tool::addBtnDataTable('remove', 'fa-trash', 'remove', 'Supprimer l\'utilisateur', ['id' => $row->getUserId()]);

            $users[] = $user;
        }

        return Tool::returnForDataTable($users);
    }

    public function get4gridUsers(): void
    {
        $pseudo = $_POST['pseudo'];
        echo $this->_get4gridUsers($pseudo);
        die;
    }

    public function addNewUser(): void
    {
        echo $this->twig->render('pages/admin/add_new_user.html.twig');
    }

    public function addNewUserToDb(): void
    {
        $userId = $this->user->getUserId();
        $userRepository = new UserRepository();
        $error = $this->_insertControl();
        if (empty($error)) {
            $userId = $userRepository->insert(['pseudo' => $_REQUEST['pseudo'],
                'email' => $_REQUEST['email'],
                'password' => $_REQUEST['password'],
                'user_type_id' => $_REQUEST['user_type_id'],
                'departement' => $_REQUEST['departement']]);
            header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/utilisateurs');
        } else {
            echo $this->twig->render('pages/admin/add_new_user.html.twig', ['errors'=>$error]);
        }

        echo $this->twig->render('pages/admin/utilisateurs.html.twig');
    }
}
