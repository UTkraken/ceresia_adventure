<?php
declare(strict_types=1);

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\LoggedController;
use ceresia_adventure\models\Trail;
use ceresia_adventure\repositories\TrailRepository;
use ceresia_adventure\utils\Tool;


class ParcoursCreateurController extends LoggedController
{
    public function index(): void
    {
        parent::__construct();
        if (!$this->isCreateur()) {
            http_response_code(403);
            exit;
        }
        $data_trails = $this->_get4gridTrails(null, $this->user->getUserId());
        echo $this->twig->render('pages/createur/trails.html.twig', ['data_trails' => $data_trails]);
    }

    private function _get4gridTrails(?string $name, ?int $userId): string
    {
        $trailRepository = new TrailRepository();
        $result = $trailRepository->select(['supprime' => 0, 'user_id' => $userId])->result();
        $trails = [];

        foreach ($result as $row) {
            $actions = '';
            $trail = [
                'name' => $row->getName(),
                'dateEnd' => Tool::dateFr($row->getDateEnd()),
                'estimatedTime' => $row->getEstimatedTime(),
                'level' => $row->getLevel(),
                'nbEnigmas' => $row->getNbEnigmas(),
                'rating' => $row->getRating(),
            ];
            $trail['test'] = Tool::addBtnRedirectDataTable('test', 'fa-play', '/parcours/play?id=' . $row->getTrailId(), 'Tester le parcours');
            if ($row->isVisible()) {
                $actions .= Tool::addBtnDataTable('visible', 'fa-eye-slash', 'visible', 'Rendre le parcours privé', ['id' => $row->getTrailId(), 'visible' => (int)!$row->isVisible()]);
            } else {
                $actions .= Tool::addBtnDataTable('visible', 'fa-eye', 'visible', 'Rendre le parcours public', ['id' => $row->getTrailId(), 'visible' => (int)!$row->isVisible()]);
            }
            $actions .= Tool::addBtnRedirectDataTable('edit', 'fa-pencil', '/parcoursCreateur/edit?id=' . $row->getTrailId(), 'Modifier le parcours');
            $actions .= Tool::addBtnRedirectEditDataTable($row->getTrailId(), 'fa-pencil', '/enigma', 'Modifier les énigmes');
            $actions .= Tool::addBtnDataTable('remove', 'fa-trash', 'remove', 'Supprimer le parcours', ['id' => $row->getTrailId()]);

            $trail['actions'] = $actions;
            $trails[] = $trail;

        }
        return Tool::returnForDataTable($trails);
    }

    public function get4gridTrails(): void
    {
        $name = $_POST['name'];
        $userId = $this->user->getUserId();

        echo $this->_get4gridTrails($name, $userId);
    }

    public function addNewTrack(): void
    {
        echo $this->twig->render('pages/createur/add_new_track.html.twig');
    }
    public function addTrack(): void
    {
        $userId = $this->user->getUserId();
        $trailRepository = new TrailRepository();
        $error = $this->_insertTrackControl();
        if (empty($error)) {
            $trailId = $trailRepository->insert(
                [
                   'name' => $_REQUEST['nom'],
                   'departement' => $_REQUEST['departement'],
                   'estimated_time' => $_REQUEST['duree'],
                   'level' => $_REQUEST['niveau'],
                   'user_id' => $userId,
                   'description' => $_REQUEST['description'],
                   'date_start' => $_REQUEST['date_debut'],
                   'date_end' => $_REQUEST['date_fin']
                ]
            );
            $trail = $trailRepository->findById((int)$trailId)->row();
            $_SESSION['trailInfo'] = $trail;
            header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/parcoursCreateur');
        } else {
            echo $this->twig->render('pages/createur/add_new_track.html.twig', ['errors'=>$error]);
        }

        echo $this->twig->render('pages/createur/add_new_track.html.twig');
    }

    public function visible(): void
    {
        $id = $_POST['id'];
        $visible = $_POST['visible'];

        $trailRepository = new TrailRepository();
        echo $trailRepository->update(['visible' => $visible], ['trail_id' => $id]);
    }

    public function remove(): void
    {
        $id = $_POST['id'];

        $trailRepository = new TrailRepository();
        echo $trailRepository->update(['supprime' => 1], ['trail_id' => $id]);
    }

    private function _insertTrackControl(): array
    {
        $trailRepository = new TrailRepository();
        $trailVerif = $trailRepository->select(['name' => $_REQUEST['nom']])->row();
        $errors = [];
        if ($trailVerif != null) {
            $errors[] = 'Le parcours existe déjà';
        }
        return $errors;
    }
}
