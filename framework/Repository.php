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

        // Get the current class (EnigmaRepository, TrailRepository...) including its path and remove the 'Repository' part
        // Then replace the 'repositories' part of the path with 'models'. We now have the path to the model corresponding to the repository
        $this->model = str_replace('Repository', '', str_replace('repositories', 'models', get_class($this)));

        // Remove the model path, transform the name into camelCase and append an "s". This gives us the table used in db
        // ex : 'somepath/models/Trail' will become 'trails'
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
        $this->data = [$query->fetch(PDO::FETCH_ASSOC)];
        return $this;
    }
    /**
     * Récupère une ligne de la table correspondant à l'objet en fonction de l'id
     * @param int $id
     * @return Repository
     */
    public function findAll(): Repository
    {
        $query = $this->db->query('SELECT * from ' . $this->table);
        $this->data = [$query->fetch(PDO::FETCH_ASSOC)];
        return $this;
    }

    /**
     * @param array $data
     *
     * @return bool|string
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
     * Met à jour les datas passées en paramètre sur la table correspondant à l'objet en fonction du paramètre where
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
     * @param int $id
     *
     * @return int
     */
    public function delete(int $id): int
    {
        $sanitizedId = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM " . $this->table . " WHERE $this->id=" . $sanitizedId;
        $query = $this->db->query($sql);
        return $query->rowCount();
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function deleteEnigma(int $id): int
    {
        $sanitizedId = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $sql = "DELETE FROM enigmas WHERE enigma_id=$sanitizedId";
        $query = $this->db->query($sql);
        return $query->rowCount();
    }
    /**
     * Récupère les lignes de la table correspondant à l'ob jet en fonction du paramètre where
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
        $this->data = $query->fetchAll(PDO::FETCH_ASSOC);

        return $this;
    }

    /** Verify if 'where' conditions exist and if so, append them to the sql query
     *
     * @param string $sql
     * @param array  $where
     */
    protected function handleWhere(string &$sql, array $where = [])
    {
        $conditions = [];
        foreach ($where as $column => $value) {
            if (isset($value)) {
                if (gettype($value) == "string") {
                    if (str_contains($value, '%')) {
                        $conditions[] = $column . " LIKE '" . $value . "'";
                    } else {
                        $conditions[] = $column . " = '" . $value . "'";
                    }
                } else {
                    $conditions[] = $column . " = " . $value;
                }
            }
        }
        if (!empty($conditions)) {
            $sql_conditions = implode(' AND ', $conditions);
            $sql .= " WHERE " . $sql_conditions;
        }
    }

    protected function handleOrder(string &$sql, ?array $orderColumns, ?array $orderDirections)
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

    protected function handleDataUpdate(string &$sql, $data)
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

    protected function handleDataInsert(string &$sql, array $data)
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

    public function row(): ?Model
    {
        if (empty($this->data)) {
            return null;
        }
        return $this->model::populate($this->data[0]);
    }

    public function result(): array
    {
        $elements = [];
        foreach ($this->data as $row) {
            $elements[] = $this->model::populate($row);
        }
        return $elements;
    }

    public function result_array(): array
    {
        return $this->data;
    }

}
