<?php
// Check if we're on production or local environment
if (strpos($_SERVER['HTTP_HOST'], 'ldpa-tech.fr') !== false) {
    // Production database configuration
    $servername = "localhost"; // Usually stays as localhost
    $username = "delo5366_MangaHeaven_Account"; // Updated username
    $password = "m@Vw6XndBrbumVaU.-e!QqyV"; // Updated password
    $dbname = "delo5366_mangaheaven"; // Assumed database name based on username pattern
} else {
    // Local development database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mangaheaven";
}

// Create connection with error handling
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to ensure proper handling of special characters
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    // More user-friendly error handling
    if (strpos($_SERVER['HTTP_HOST'], 'ldpa-tech.fr') !== false) {
        // On production, don't show detailed errors
        die("Une erreur de connexion à la base de données s'est produite. Veuillez contacter l'administrateur.");
    } else {
        // In development, show detailed errors
        die("Database connection error: " . $e->getMessage());
    }
}
?>
