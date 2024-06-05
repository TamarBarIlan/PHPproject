<?php

include 'Database.php';

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