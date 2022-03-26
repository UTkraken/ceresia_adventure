<?php
declare(strict_types=1);

namespace ceresia_adventure\controllers;

use ceresia_adventure\framework\LoggedController;
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

        $result = $trailRepository->select(['supprime' => 0, 'name' => '%' . $name . '%', 'user_id' => $userId])->result();

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
            $actions .= Tool::addBtnDataTable('edit', 'fa-trash', 'remove', 'Supprimer le parcours', ['id' => $row->getTrailId()]);

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
}
