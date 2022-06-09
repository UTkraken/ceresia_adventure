<?php

namespace ceresia_adventure\framework;

use ceresia_adventure\models\User;
use ceresia_adventure\repositories\UserRepository;
use ceresia_adventure\utils\Config;
use ceresia_adventure\utils\Constantes;

abstract class LoggedController extends Controller
{
    protected User $user;

    public function __construct()
    {
        parent::__construct();
        if(!$this->isLogged()) {
            header('Location: ' .  'http://' . $_SERVER['HTTP_HOST'] . '/login');
        }
        $this->user = $_SESSION['userInfo'];
        $this->twig->addGlobal('isCreateur', $this->isCreateur());
        $this->twig->addGlobal('isJoueur', $this->isJoueur());
        $this->twig->addGlobal('isAdmin', $this->isAdmin());

    }

    public function isCreateur(): bool{
        return isset($_SESSION['userInfo']) && $_SESSION['userInfo']->getUserType()->getUserTypeId() === Constantes::USER_TYPE_CREATEUR || $this->isAdmin();
    }

    public function isJoueur(): bool {
        return isset($_SESSION['userInfo']) && $_SESSION['userInfo']->getUserType()->getUserTypeId() === Constantes::USER_TYPE_JOUEUR || $this->isAdmin();
    }

    public function isAdmin(): bool{
        return isset($_SESSION['userInfo']) && $_SESSION['userInfo']->getUserType()->getUserTypeId() === Constantes::USER_TYPE_ADMINISTRATEUR;
    }

    protected function _insertControl(): array
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
