<?php

include 'Database.php';
$db = new Database("localhost", "root", "", "test");
$db->connect();

// Define the SQL query to fetch active users and their posts
$sql = "
SELECT users.id AS user_id, users.name, users.email, users.active,
       posts.id AS post_id, posts.title, posts.content, posts.creation_date
FROM users
LEFT JOIN posts ON users.id = posts.user_id
WHERE users.active = 'yes'
ORDER BY users.id, posts.creation_date DESC;
";

$result = $db->executeQuery($sql);

if ($result->num_rows > 0) {
    $currentUserId = null;
    while ($row = $result->fetch_assoc()) {
        if ($currentUserId != $row['user_id']) {
            if ($currentUserId !== null) {
                echo "</div>";
            }
            $currentUserId = $row['user_id'];
            echo "<div class='user'>";
            echo "<img src='default-avatar.jpg' alt='User Image' style='width:50px;height:50px;border-radius:50%;'>";
            echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
            echo "<p>Email: " . htmlspecialchars($row['email']) . "</p>";
        }

        if (!empty($row['post_id'])) {
            echo "<div class='post'>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
            echo "<small>Posted on: " . $row['creation_date'] . "</small>";
            echo "</div>";
        }
    }
    echo "</div>";
} else {
    echo "No active users found.";
}

$db->disconnect();

?>

<!-- Some basic CSS to style the output -->
<style>
    .user {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }
    .post {
        border-top: 1px solid #ccc;
        padding-top: 10px;
        margin-top: 10px;
    }
</style>
