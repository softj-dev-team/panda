<?php
// models/CRUD.php

require_once 'Database.php';

class CRUD {
    protected $db;
    protected $table;

    public function __construct($table) {
        $this->db = (new Database())->connect();
        $this->table = $table;
    }

    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        $stmt = $this->db->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function update($data, $where) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $set = implode(", ", $set);

        $where_clause = [];
        foreach ($where as $key => $value) {
            $where_clause[] = "$key = :where_$key";
        }
        $where_clause = implode(" AND ", $where_clause);

        $query = "UPDATE {$this->table} SET $set WHERE $where_clause";
        $stmt = $this->db->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }

        return $stmt->execute();
    }

    public function delete($where) {
        $where_clause = [];
        foreach ($where as $key => $value) {
            $where_clause[] = "$key = :$key";
        }
        $where_clause = implode(" AND ", $where_clause);

        $query = "DELETE FROM {$this->table} WHERE $where_clause";
        $stmt = $this->db->prepare($query);

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function select($where = []) {
        $query = "SELECT * FROM {$this->table}";
        if (!empty($where)) {
            $where_clause = [];
            foreach ($where as $key => $value) {
                $where_clause[] = "$key = :$key";
            }
            $where_clause = implode(" AND ", $where_clause);
            $query .= " WHERE $where_clause";
        }

        $stmt = $this->db->prepare($query);
        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function selectWithOrderAndLimit($where = [], $order = '', $limit = '') {
        $query = "SELECT * FROM {$this->table}";

        if (!empty($where)) {
            $where_clause = [];
            foreach ($where as $key => $value) {
                $where_clause[] = "$key = :$key";
            }
            $where_clause = implode(" AND ", $where_clause);
            $query .= " WHERE $where_clause";
        }

        if (!empty($order)) {
            $query .= " ORDER BY $order";
        }

        if (!empty($limit)) {
            $query .= " LIMIT $limit";
        }

        $stmt = $this->db->prepare($query);

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>
