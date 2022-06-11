<?php
declare(strict_types=1);

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\models\Enigma;
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
            $trailId = intval($_SESSION['trail_id']);
        }
        $data_enigmas = $this->_get4gridEnigmas(intval($trailId));
        echo $this->twig->render('pages/createur/enigma.html.twig', ['data_enigmas' => $data_enigmas, 'trail_id' => $trailId]);
    }

    public function remove(): void
    {
        $id = $_POST['id'];
        $enigmaRepository = new EnigmaRepository();
        echo $enigmaRepository->deleteEnigma(intval($id));
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
        /** @var Enigma $row */
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
                'hint'          => $row->getHint()
            ];
            $_SESSION['trail_id'] = $row->getTrail()->getTrailId();

            $actions .= Tool::addBtnRedirectDataEdit($row->getEnigmaId(), "enigma_id", 'fa-pencil', '/enigma/editEnigma', 'Modifier l\'énigme');
            $actions .= Tool::addBtnDataTable('remove', 'fa-trash', 'remove', 'Supprimer l\'énigme', ['id' => $row->getEnigmaId()]);
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
                    'name' => $this->sanitizeTextInput($_REQUEST['name']),
                    'question' => $this->sanitizeTextInput($_REQUEST['question']),
                    'answer' => $this->sanitizeTextInput($_REQUEST['answer']),
                    'hint' => $this->sanitizeTextInput($_REQUEST['hint']),
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

    public function editEnigma(): void
    {
        $enigmaRepository = new EnigmaRepository();
        $enigmaId = $_POST['enigma_id'];

        $result = $enigmaRepository->select(['enigma_id' => $enigmaId])->result();

        echo $this->twig->render('pages/admin/edit_enigma.html.twig', ['enigma' => $result, 'enigma_id' =>$enigmaId]);
    }

    public function insertEnigma(): void
    {
        $enigmaRepository = new EnigmaRepository();


        if (empty($error)) {
            $enigma = $enigmaRepository->update(
                [
                    'name' => $this->sanitizeTextInput($_REQUEST['name']),
                    'question' => $this->sanitizeTextInput($_REQUEST['question']),
                    'answer' => $this->sanitizeTextInput($_REQUEST['answer']),
                    'hint' => $this->sanitizeTextInput($_REQUEST['hint']),
                    'difficulty' => $_REQUEST['difficulty'],
                    'estimated_time' => $_REQUEST['estimatedTime'],
                    'image_url' => "Image path"
                ],
                ['enigma_id' => $_REQUEST['enigma_id']]
            );
            header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/enigma');
        } else {
            echo '<div class="something">' . $error[0];
        }

        echo $this->twig->render('pages/createur/edit_enigma.html.twig');
    }

    protected function _insertControl(): array
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
