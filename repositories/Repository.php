<?php

abstract class Repository
{
    protected Database $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->table = str_replace('repository', '', strtolower(get_class($this)));
    }

    public function findById(int $id)
    {
        $query = $this->db->query('SELECT * from ' . $this->table . ' where testId = ' . $id);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @throws Exception
     */
    public function insert(array $data)
    {
        if (empty($data)) {
            throw new Exception("Insert without data");
        }
        $sql = "INSERT INTO " . $this->table;
        $this->handleDataInsert($sql, $data);
        $query = $this->db->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @throws Exception
     */
    public function update(array $data, ?array $where = [])
    {
        if (empty($data)) {
            throw new Exception("Insert without data");
        }
        $sql = "UPDATE " . $this->table;
        $this->handleDataUpdate($sql, $data);
        $this->handleWhere($sql, $where);
        $query = $this->db->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function select(?array $where = []): ?array
    {

        $sql = "SELECT * FROM " . $this->table;
        $this->handleWhere($sql, $where);
        $query = $this->db->query($sql);
        return $query->fetchAll(PDO::FETCH_ASSOC);
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
            $sql_conditions = implode(' AND', $conditions);
            $sql .= " WHERE " . $sql_conditions;
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
}