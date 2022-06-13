<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\models\User;
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
        echo $userRepository->delete($id);
    }

    private function _get4gridUsers(?string $pseudo): string
    {
        $userRepository = new UserRepository();
        $result = $userRepository->select(['pseudo' => '%' . $pseudo])->result();

        $users = [];
        foreach ($result as $row) {
            $user = [
                'user_id' => $row->getUserId(),
                'pseudo' => $row->getPseudo(),
                'email' => $row->getEmail(),
            ];
            $user['actions'] = Tool::addBtnRedirectUserEditDataTable($row->getUserId(), 'fa-pencil', '/utilisateurs/editUser', 'Modifier l\'utilisateur');
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
        if ($_REQUEST['user_type_id'] == '') {
            array_push($error, 'Veuillez sélectionner un type de compte');
        }

        if (empty($error)) {
            $userId = $userRepository->insert(
                [
                    'pseudo' => $this->sanitizeTextInput($_REQUEST['pseudo']),
                    'email' => $_REQUEST['email'],
                    'password' => password_hash($_REQUEST['password'],PASSWORD_DEFAULT),
                    'user_type_id' => $_REQUEST['user_type_id'],
                    'departement' => $_REQUEST['departement']]);
            header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/utilisateurs');
        } else {
            echo $this->twig->render('pages/admin/add_new_user.html.twig', ['errors'=>$error]);
        }

        echo $this->twig->render('pages/admin/utilisateurs.html.twig');
    }

    public function editUser(): void
    {
        $userRepository = new UserRepository();
        $userId = $_POST['user_id'];

        $result = $userRepository->select(['user_id' => $userId])->row();

        echo $this->twig->render('pages/admin/edit_user.html.twig', ['user' => $result, 'user_id' => $_POST['user_id']]);
    }

    public function insertUser(): void
    {
        $userRepository = new UserRepository();

        $errors = [];
        $userId =$_REQUEST['user_id'];

        /** @var User $user */
        $user = $userRepository->select(['user_id' => $userId])->row();

        $isMailUnique = $this->isEmailUnique($_REQUEST['email'], $userId, $user );
        if(!$isMailUnique) {
            array_push($errors, 'Ce mail est déjà pris');
        }

        if (empty($errors)) {
            $user = $userRepository->update(
                [
                    'pseudo' => $_REQUEST['pseudo'],
                    'email' => $_REQUEST['email'],
                ],
                ['user_id' => $_REQUEST['user_id']]
            );
            header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/utilisateurs');
        }
        echo $this->twig->render('pages/admin/edit_user.html.twig', ['user' => $user, 'user_id' => $userId, 'errors' => $errors]);
    }

    /**
     * Verify that the email entered is not already used by another user
     * @param string $mail
     * @param int    $userId
     * @param User   $user
     *
     * @return bool
     */
    protected function isEmailUnique(string $mail, int $userId, User $user): bool
    {
        $userRepository = new UserRepository();

        if($mail != $user->getEmail())
        {
            /** @var User $userVerif */
            $userVerif = $userRepository->select(['email' => $mail ])->result();
            foreach ($userVerif as $u)
            {
                if ($u->getUserId() != $userId)
                {
                    return false;
                }
            }
        }

        return true;
    }

}
