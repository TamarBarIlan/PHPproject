<?php

include 'Database.php';
$db = new Database("localhost", "root", "", "test");
$db->connect();

$sql = "SELECT 
            DATE(creation_date) AS Date,
            HOUR(creation_date) AS Hour,
            COUNT(*) AS 'Amount of posts for that hour'
        FROM 
            posts
        GROUP BY 
            DATE(creation_date), 
            HOUR(creation_date)
        ORDER BY 
            Date, 
            Hour;";

$result = $db->executeQuery($sql);

if ($result->num_rows > 0) {
    // Start HTML table
    echo "<table border='1'>
            <tr>
                <th>Date</th>
                <th>Hour</th>
                <th>Amount of posts for that hour</th>
            </tr>";
    
    // Fetch and display each row of data
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['Date'] . "</td>
                <td>" . $row['Hour'] . "</td>
                <td>" . $row['Amount of posts for that hour'] . "</td>
              </tr>";
    }

    // End HTML table
    echo "</table>";
} else {
    echo "No posts found.";
}

$db->disconnect();

?>
