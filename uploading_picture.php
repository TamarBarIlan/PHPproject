<?php

$imageUrl = "https://cdn2.vectorstock.com/i/1000x1000/23/81/default-avatar-profile-icon-vector-18942381.jpg";
$savePath = __DIR__ . "/default-avatar.jpg";

$imageData = file_get_contents($imageUrl);

if ($imageData === FALSE) {
    die("Error: Unable to fetch the image from the URL.");
}

$result = file_put_contents($savePath, $imageData);

if ($result === FALSE) {
    die("Error: Unable to save the image on the server.");
}

echo "Image successfully saved as $savePath.";

?>