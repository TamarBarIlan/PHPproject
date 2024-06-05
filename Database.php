<?php

class Database {
    private $host;
    private $username;
    private $password;
    private $database;
    private $conn;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function disconnect() {
        $this->conn->close();
    }

    public function executeQuery($sql) {
        return $this->conn->query($sql);
    }

    public function select($table, $columns = "*", $condition = "") {
        $sql = "SELECT $columns FROM $table";
        if (!empty($condition)) {
            $sql .= " WHERE $condition";
        }
        return $this->executeQuery($sql);
    }

    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        return $this->executeQuery($sql);
    }

    public function delete($table, $condition) {
        $sql = "DELETE FROM $table WHERE $condition";
        return $this->executeQuery($sql);
    }

    public function update($table, $data, $condition) {
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "$key = '$value', ";
        }
        $set = rtrim($set, ", ");
        $sql = "UPDATE $table SET $set WHERE $condition";
        return $this->executeQuery($sql);
    }

}

?>
