<?php

namespace ceresia_adventure\repositories;

use ceresia_adventure\framework\Repository;
use PDO;

class TrailRepository extends Repository {

    /**
     * Retourne le nombre de parcours créé par un créateur
     * @param int $userId
     * @return array
     */
    public function getNbTrailByCreator(int $userId): int
    {
        $sql = "SELECT count(1) as count FROM trails";
        $this->handleWhere($sql, ['user_id' => $userId]);
        $query = $this->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC)['count'];
    }
}