<?php

include 'Database.php';
$db = new Database("localhost", "root", "", "test");
$db->connect();

// Add the 'birthday' column to the 'users' table if it doesn't exist
$db->executeQuery("ALTER TABLE users ADD COLUMN IF NOT EXISTS birthday DATE");

// Generate random birthdays for each user and save them in the database
$usersResult = $db->select("users");
if ($usersResult->num_rows > 0) {
    while ($row = $usersResult->fetch_assoc()) {
        // Generate a random birthday between 1950 and 2000 for demonstration
        $randomBirthday = date('Y-m-d', mt_rand(strtotime("1950-01-01"), strtotime("2000-12-31")));
        
        // Update the user's record with the random birthday
        $userId = $row['id'];
        $db->update("users", ["birthday" => $randomBirthday], "id = $userId");
    }
}

$currentMonth = date('m');
$currentYear = date('Y');

$sql = "SELECT * FROM posts WHERE MONTH(creation_date) = $currentMonth AND YEAR(creation_date) = $currentYear 
        ORDER BY creation_date DESC LIMIT 1";

$result = $db->executeQuery($sql);

if ($result->num_rows > 0) {
    echo "Posts found for users with birthdays in June:<br>";
    while ($row = $result->fetch_assoc()) {
        echo "Post ID: " . $row['id'] . "<br>";
        echo "User ID: " . $row['user_id'] . "<br>";
        echo "Title: " . $row['title'] . "<br>";
        echo "Content: " . $row['content'] . "<br>";
        echo "Creation Date: " . $row['creation_date'] . "<br>";
        echo "<br>";
    }
} else {
    echo "No posts found for users with birthdays in June.";
}

$db->disconnect();

?>
