<?php

namespace ceresia_adventure\framework;

use ceresia_adventure\utils\Config;
use PDO;
use PDOStatement;

class Database extends PDO
{
    private ?PDO $PDOInstance;

    private static ?Database $instance = null;

    private function __construct()
    {
        $config = (new Config())->config;
        $this->PDOInstance = new PDO($config['DATABASE_DRIVER'] . ':dbname='.$config['DATABASE_DBNAME'].';host='.$config['DATABASE_HOST'].';port='.$config['DATABASE_PORT'] . ";charset=utf8mb4",$config['DATABASE_USER'] ,$config['DATABASE_PASSWORD']);
    }

    public static function getInstance(): ?Database
    {
        if(is_null(self::$instance))
        {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Exécute une requête SQL avec PDO
     * @param $statement
     * @param int $mode
     * @param mixed ...$fetch_mode_args
     * @return PDOStatement Retourne l'objet PDOStatement
     */
    public function query($statement, $mode = PDO::FETCH_ASSOC, ...$fetch_mode_args): PDOStatement
    {
        return $this->PDOInstance->query($statement, $mode, ...$fetch_mode_args);
    }
}