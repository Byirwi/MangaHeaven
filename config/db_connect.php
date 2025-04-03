<?php
// Database configuration
$servername = "localhost";
$username = "root"; // default WAMP username
$password = ""; // default WAMP password is empty
$dbname = "mangaheaven";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to ensure proper handling of special characters
$conn->set_charset("utf8mb4");
?>
