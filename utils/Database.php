<?php

class Database extends PDO
{
    private ?PDO $PDOInstance;

    private static $instance = null;

    public const DEFAULT_SQL_USER = 'root';

    public const DEFAULT_SQL_HOST = 'localhost';

    public const DEFAULT_SQL_PASS = '';

    public const DEFAULT_SQL_DTB = 'ceresia_adventure';

    public const DEFAULT_SQL_PORT = 3306;

    private function __construct()
    {
        $this->PDOInstance = new PDO('mysql:dbname='.self::DEFAULT_SQL_DTB.';host='.self::DEFAULT_SQL_HOST .';port='. self::DEFAULT_SQL_PORT,self::DEFAULT_SQL_USER ,self::DEFAULT_SQL_PASS);
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