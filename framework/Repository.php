<?php

namespace ceresia_adventure\framework;

use ceresia_adventure\utils\Config;
use ceresia_adventure\utils\Tool;
use ceresia_adventure\models;
use Exception;
use PDO;
use PDOStatement;

abstract class Repository
{
    protected Database $db;
    protected string $table;
    protected string $model;
    protected string $id;
    protected array $config;
    protected array $data;

    /**
     * Constructeur de la classe Repository
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->model = str_replace('Repository', '', str_replace('repositories', 'models', get_class($this)));
        $this->table = Tool::addSToSnakeCase(Tool::camelCaseToSnakeCase(str_replace('ceresia_adventure\models\\', '', $this->model)));
        $this->id = Tool::camelCaseToSnakeCase(str_replace('ceresia_adventure\models\\', '', $this->model)) . '_id';
        $this->config = (new Config())->config;
    }

    /**
     * Récupère une ligne de la table correspondant à l'objet en fonction de l'id
     * @param int $id
     * @return Repository
     */
    public function findById(int $id): Repository
    {
        $query = $this->db->query('SELECT * from ' . $this->table . ' where ' . $this->id . ' = ' . $id);
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $this->data = [$this->model::populate($row)];
        return $this;
    }

    /**
     * @throws Exception
     */
    public function insert(array $data): bool|string
    {
        if (empty($data)) {
            throw new Exception("Insert without data");
        }
        $sql = "INSERT INTO " . $this->table;
        $this->handleDataInsert($sql, $data);
        $this->db->query($sql);
        return $this->db->lastInsertId();
    }

    /**
     * Met à jour les data passer en paramètre sur la table correspondant à l'objet en fonction du paramètre where
     * @param array $data
     * @param array|null $where
     * @return int
     * @throws Exception
     */
    public function update(array $data, ?array $where = []): int
    {
        if (empty($data)) {
            throw new Exception("Insert without data");
        }
        $sql = "UPDATE " . $this->table;
        $this->handleDataUpdate($sql, $data);
        $this->handleWhere($sql, $where);
        $query = $this->db->query($sql);
        return $query->rowCount();
    }

    /**
     * Récupère les lignes de la table correspondant à l'objet en fonction du paramètre where
     * @param array|null $where
     * @param array|null $orderColumns
     * @param array|null $orderDirections
     * @param string|null $limit
     * @param string|null $offset
     * @return array|null
     */
    public function select(?array $where = [], ?array $orderColumns = null, ?array $orderDirections = null, ?string $limit = null, ?string $offset = null): ?Repository
    {
        $sql = "SELECT * FROM " . $this->table;
        $this->handleWhere($sql, $where);
        if (isset ($orderColumn, $orderDirection)) {
            $this->handleOrder($sql, $orderColumns, $orderDirections);
        }
        if (isset($limit)) {
            $sql .= " LIMIT " . $limit;
        }
        if (isset($offset)) {
            $sql .= " OFFSET " . $offset;
        }
        $query = $this->db->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $elements = [];
        foreach ($result as $row) {
            $elements[] = $this->model::populate($row);
        }
        $this->data = $elements;
        return $this;
    }

    private function handleWhere(string &$sql, array $where = [])
    {
        $conditions = [];
        foreach ($where as $column => $value) {
            if (gettype($value) == "string") {
                $conditions[] = $column . " = '" . $value . "'";
            } else {
                $conditions[] = $column . " = " . $value;
            }
        }
        if (!empty($conditions)) {
            $sql_conditions = implode(' AND ', $conditions);
            $sql .= " WHERE " . $sql_conditions;
        }
    }

    private function handleOrder(string &$sql, ?array $orderColumns, ?array $orderDirections)
    {
        $orders = [];
        foreach ($orderColumns as $key => $column) {
            $direction = $orderDirections[$key] ?? "ASC";
            if (isset ($orderDirections[$key])) {
                $orders[] = $column . " " . $direction;
            }
        }

        if (!empty($orders)) {
            $sql_conditions = implode(', ', $orders);
            $sql .= " ORDER BY " . $sql_conditions;
        }
    }

    private function handleDataUpdate(string &$sql, $data)
    {
        $values = [];
        foreach ($data as $column => $value) {
            if (gettype($value) == "string") {
                $values[] = $column . " = '" . $value . "'";
            } else {
                $values[] = $column . " = " . $value;
            }
        }
        $sql_conditions = implode(', ', $values);
        $sql .= " SET " . $sql_conditions;
    }

    private function handleDataInsert(string &$sql, array $data)
    {
        $columns = [];
        $values = [];
        foreach ($data as $column => $value) {
            $columns[] = $column;
            if (gettype($value) == "string") {
                $values[] = "'" . $value . "'";
            } else {
                $values[] = $value;
            }
        }
        $sql_columns = implode(', ', $columns);
        $sql_values = implode(', ', $values);
        $sql .= " ($sql_columns) VALUES ($sql_values)";
    }

    /**
     * Exécute une requête SQL avec PDO
     * @param $statement
     * @param int $mode
     * @param mixed ...$fetch_mode_args
     * @return PDOStatement Retourne l'objet PDOStatement
     */
    public function query($statement, int $mode = PDO::FETCH_ASSOC, ...$fetch_mode_args): PDOStatement
    {
        return $this->db->query($statement, $mode, ...$fetch_mode_args);
    }

    public function row(): mixed
    {
        return $this->data[0] ?? null;
    }

    public function result(): array
    {
        return $this->data;
    }

}