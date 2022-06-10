<?php
declare(strict_types=1);

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\repositories\EnigmaRepository;
use ceresia_adventure\utils\Tool;

/**
 * Class EnigmaController
 * @package ceresia_adventure\controllers
 *
 */
class EnigmaController extends LoggedController
{
    public function index(): void
    {
        parent::__construct();
        if (!$this->isCreateur()) {
            http_response_code(403);
            exit;
        }
        if(isset($_POST['trail_id']))
        {
            $trailId = $_POST['trail_id'];
        } elseif(isset($_SESSION['trail_id']))
        {
            $trailId = $_SESSION['trail_id'];
        }
        $data_enigmas = $this->_get4gridEnigmas(intval($trailId));
        echo $this->twig->render('pages/createur/enigma.html.twig', ['data_enigmas' => $data_enigmas, 'trail_id' => $trailId]);
    }

    /**
     * @param int $trailId
     *
     * @return string
     */
    private function _get4gridEnigmas(int $trailId): string
    {
        $enigmaRepository = new EnigmaRepository();
        $result           = $enigmaRepository->select(['trail_id' => $trailId])->result();
        $enigmas          = [];

        foreach ($result as $row) {
            $actions           = '';
            $enigma            = [
                'trail_id'      => $row->getTrail()->getTrailId(),
                'name'          => $row->getName(),
                'image'         => $row->getImage(),
                'question'      => $row->getQuestion(),
                'answer'        => $row->getAnswer(),
                'difficulty'    => $row->getDifficulty(),
                'estimatedTime' => $row->getEstimatedTime(),
                'hint'          => $row->getHint(),
            ];
//            $actions           .= Tool::addBtnRedirectDataTable('edit', 'fa-pencil', '/enigma/edit?id=' . '5', 'Modifier énigme');
//            $actions           .= Tool::addBtnDataTable('remove', 'fa-trash', 'remove', 'Supprimer énigme', ['id' => '5']);
            $actions .= "seomthing";
            $enigma['actions'] = $actions;
            $enigmas[]         = $enigma;
        }

         return Tool::returnForDataTable($enigmas);
    }


    public function get4gridEnigmas(): void
    {
        $trailId = $_POST['trail_id'];

        echo $this->_get4gridEnigmas(intval($trailId));
    }

    public function addNewEnigma(): void
    {
        $trailId = $_POST['trail_id'];
        echo $this->twig->render(
            'pages/createur/add_new_enigma.html.twig',
            ['trail_id' => $trailId]);
    }
    public function addEnigma(): void
    {
        $enigmaRepository = new EnigmaRepository();
        if (empty($error)) {
            $enigmaId = $enigmaRepository->insert(
                [
                    'name' => $_REQUEST['name'],
                    'question' => $_REQUEST['question'],
                    'answer' => $_REQUEST['answer'],
                    'hint' => $_REQUEST['hint'],
                    'difficulty' => $_REQUEST['difficulty'],
                    'estimated_time' => $_REQUEST['estimatedTime'],
                    'trail_id' => $_REQUEST['trail_id'] ,
                    'image_url' => "Image path"
                ]
            );
            $enigma = $enigmaRepository->findById((int)$enigmaId)->row();
            $_SESSION['enigmaInfo'] = $enigma;
            $_SESSION['trail_id'] = intval($_REQUEST['trail_id']);
            header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/enigma');
        } else {
            echo $this->twig->render('pages/createur/add_new_enigma.html.twig', ['errors'=>$error]);
        }

        echo $this->twig->render('pages/createur/add_new_enigma.html.twig', ['trail_id' => $_REQUEST['trail_id']]);
    }

    public function visible(): void
    {
        $id = $_POST['id'];
        $visible = $_POST['visible'];

        $enigmaRepository = new EnigmaRepository();
        echo $enigmaRepository->update(['visible' => $visible], ['enigma_id' => $id]);
    }

    public function remove(): void
    {
        $id = $_POST['id'];

        $enigmaRepository = new EnigmaRepository();
        echo $enigmaRepository->update(['supprime' => 1], ['enigma_id' => $id]);
    }

    private function _insertControl(): array
    {
        $enigmaRepository = new EnigmaRepository();
        $enigmaVerif = $enigmaRepository->select(['name' => $_REQUEST['name']])->row();
        $errors = [];
        if ($enigmaVerif != null) {
            $errors[] = 'énigme existe déjà';
        }
        return $errors;
    }
}
