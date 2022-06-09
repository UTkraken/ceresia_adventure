<?php
namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\repositories\UserRepository;
use ceresia_adventure\utils\Tool;

class AdministrateurController extends LoggedController
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
        echo $this->twig->render('pages/admin/admin_list.html.twig', ['adminList' => $this->_get4gridAdmin()]);
    }

    public function remove(): void
    {
        $id = $_POST['id'];

        $userRepository = new UserRepository();
        echo $userRepository->delete($id);
    }


    private function _get4gridAdmin(): string
    {
        $userRepository = new UserRepository();
        $result = $userRepository->select(['user_type_id' => 3])->result();

        $usersAdmin = [];
        foreach ($result as $row) {
            $usersAdmin = [
                'user_id' => $row->getUserId(),
                'pseudo' => $row->getPseudo(),
                'email' => $row->getEmail(),
            ];
            $usersAdmin['actions'] = Tool::addBtnDataTable('edit', 'fa-pencil', 'edit', 'Modifier l\'utilisateur', ['id' => $row->getUserId()]);
            $usersAdmin['actions'] .= Tool::addBtnDataTable('remove', 'fa-trash', 'remove', 'Supprimer l\'utilisateur', ['id' => $row->getUserId()]);

            $admins[] = $usersAdmin;
        }
        return Tool::returnForDataTable($admins);
    }

    public function get4gridAdmin(): void
    {
        echo $this->_get4gridAdmin();
        die;
    }
}