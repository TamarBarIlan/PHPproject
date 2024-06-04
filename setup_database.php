<?php

require_once 'Database.php';

$database = new Database();
$db = $database->connect();

$sql = "
CREATE DATABASE IF NOT EXISTS test_db;

USE test_db;

CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    active ENUM('yes', 'no') NOT NULL
);

CREATE TABLE IF NOT EXISTS Posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    active ENUM('yes', 'no') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(id)
);
";

try {
    $db->exec($sql);
    echo "Database and tables created successfully.";
} catch (PDOException $e) {
    echo "Error creating database and tables: " . $e->getMessage();
}

?>
