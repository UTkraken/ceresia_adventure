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
            $actions = '';
            $usersAdmin = [
                'user_id' => $row->getUserId(),
                'pseudo' => $row->getPseudo(),
                'email' => $row->getEmail(),
            ];
            $actions .= Tool::addBtnRedirectUserEditDataTable($row->getUserId(), 'fa-pencil', '/administrateur/editUser', 'Modifier l\'utilisateur');
            $actions .= Tool::addBtnDataTable('remove', 'fa-trash', 'remove', 'Supprimer l\'utilisateur', ['id' => $row->getUserId()]);
            $usersAdmin['actions'] = $actions;
            $admins[] = $usersAdmin;


        }
        return Tool::returnForDataTable($admins);
    }


    public function editUser(): void
    {
        $userRepository = new UserRepository();
        $userId = $_POST['user_id'];

        $result = $userRepository->select(['user_id' => $userId])->result();

        echo $this->twig->render('pages/admin/edit_user.html.twig', ['user' => $result, 'user_id' => $_POST['user_id']]);
    }

    public function insertUser(): void
    {
        $userRepository = new UserRepository();

        $userId = $this->user->getUserId();
        $result = $userRepository->select(['user_id' => $userId])->result();

//        $error = $this->_insertUserControl();
        if (empty($error)) {
            $user = $userRepository->update(
                [
                    'pseudo' => $_REQUEST['pseudo'],
                    'email' => $_REQUEST['email'],
                ],
                ['user_id' => $_REQUEST['user_id']]
            );
            header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/administrateur');
        } else {
            echo '<div class="something">' . $error[0];
        }

        echo $this->twig->render('pages/createur/edit_track.html.twig');
    }

    public function get4gridAdmin(): void
    {
        echo $this->_get4gridAdmin();
        die;
    }

    // TODO Handle cases when only the pseudo or email is modified, to ignore the other unmodified value from being checked
    // i.e. Changing only the mail won't work because the name will be checked and fail because it already exists in bdd. Reverse is also possible
    private function _insertUserControl(): array
    {
        $trailRepository = new UserRepository();
        $trailVerif = $trailRepository->select(['pseudo' => $_REQUEST['pseudo']])->row();
        $errors = [];
        if ($trailVerif != null) {
            $errors[] = 'Le nom existe déjà';
        }
        return $errors;
    }
}
