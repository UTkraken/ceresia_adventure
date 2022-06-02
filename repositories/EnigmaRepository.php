<?php

namespace ceresia_adventure\repositories;

use ceresia_adventure\framework\Repository;
use PDO;

class EnigmaRepository extends Repository {
    public function countEnigmaByTrail(int $trailId) {
        $sql = "SELECT count(1) as count FROM enigmas";
        $this->handleWhere($sql, ['trail_id' => $trailId]);
        $query = $this->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC)['count'];
    }
}