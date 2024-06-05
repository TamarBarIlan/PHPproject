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

$db = new Database("localhost", "root", "", "test");
$db->connect();

// Create the 'users' table if it doesn't exist
$db->executeQuery("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    active ENUM('yes', 'no')
)");

// Create the 'posts' table if it doesn't exist
$db->executeQuery("CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    content TEXT,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    active ENUM('yes', 'no'),
    FOREIGN KEY (user_id) REFERENCES users(id)
)");

// Fetch data from JSONPlaceholder
$json_data = file_get_contents("https://jsonplaceholder.typicode.com/users");
$users = json_decode($json_data, true);

foreach ($users as $user) {
    $user_data = [
        'name' => $user['name'],
        'email' => $user['email'],
        'active' => 'yes'
    ];
    $db->insert('users', $user_data);
}

$json_data = file_get_contents("https://jsonplaceholder.typicode.com/posts");
$posts = json_decode($json_data, true);

foreach ($posts as $post) {
    $post_data = [
        'user_id' => $post['userId'],
        'title' => $post['title'],
        'content' => $post['body'],
        'active' => 'yes'
    ];
    $db->insert('posts', $post_data);
}

$db->disconnect();

?>
