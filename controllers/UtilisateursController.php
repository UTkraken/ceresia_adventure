<?php

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\Controller;
use ceresia_adventure\repositories\UserRepository;

class UtilisateursController extends Controller
{
    public function index(): void
    {
        echo $this->twig->render('pages/admin/user_list.html.twig', ['userList' => $this->_get4grid_users()]);
    }

    public function delete(): void
    {
        $userId = $_REQUEST['userId'];

        $userRepository = new UserRepository();
        $userRepository->delete($userId);
    }

    private function _get4grid_users()
    {
        $userRepository = new UserRepository();
        $userList       = $userRepository->select()->result_array();

        $result = [];
        foreach($userList as $user) {
            $data = $user;
            $data['actions'] = '<a href="#" class="delete" data-userid="'.$user['user_id'].'"><img src="'. $this->asset . '/' . 'img/trash.png' . '") }}"></a>';

            $result[] = $data;
        }

        return json_encode(["recordsTotal"    => 57,
                            "recordsFiltered" => 57,
                            "data" => $result]);

    }

    public function users()
    {
        echo $this->_get4grid_users();
        die;
    }
}
