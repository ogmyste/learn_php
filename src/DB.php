<?php
namespace App;

use PDO;
use PDOException;

class DB {
    private PDO $conn;

    public function __construct()
    {
        $servername = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '33061';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: 'example';
        $dbname = getenv('DB_NAME') ?: 'learnphp';

        try {
            $this->conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function all($table, $class) {
        $sql = "SELECT * FROM $table";
        // Execute the SQL query
        $result = $this->conn->query($sql);
        $result->setFetchMode(PDO::FETCH_CLASS, $class);
        return $result->fetchAll();
    }

    public function where($table, $class, $field, $value) {
        $result = $this->conn->prepare("SELECT * FROM $table WHERE $field = ?");
        $result->execute([$value]);
        $result->setFetchMode(PDO::FETCH_CLASS, $class);
        return $result->fetchAll();
    }

    public function find($table, $class, $id) {
        $result = $this->conn->prepare("SELECT * FROM $table WHERE id = ?");
        $result->execute([$id]);
        $result->setFetchMode(PDO::FETCH_CLASS, $class);
        return $result->fetch();
    }

    public function insert($table, $fields) {
        $names = implode(', ', array_keys($fields));
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $statement = $this->conn->prepare("INSERT INTO $table ($names) VALUES ($placeholders)");
        $statement->execute(array_values($fields));
    }

    public function update($table, $fields, $id) {
        $assignments = implode(', ', array_map(fn($name) => "$name = ?", array_keys($fields)));
        $statement = $this->conn->prepare("UPDATE $table SET $assignments WHERE id = ?");
        $statement->execute([...array_values($fields), $id]);
    }

    public function delete($table, $id) {
        $statement = $this->conn->prepare("DELETE FROM $table WHERE id = ?");
        $statement->execute([$id]);
    }
}
