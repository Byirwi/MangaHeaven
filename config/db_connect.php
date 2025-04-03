<?php
// Configuration pour les environnements différents
$is_o2switch = (strpos($_SERVER['HTTP_HOST'], 'ldpa-tech.fr') !== false || 
                strpos($_SERVER['SERVER_NAME'], 'ldpa-tech.fr') !== false ||
                file_exists('/home/delo5366')); // Vérification spécifique à o2switch

if ($is_o2switch) {
    // Configuration pour O2Switch
    $servername = "localhost";
    // Remplacer ces valeurs par les informations exactes fournies par O2Switch
    $username = "delo5366_manga"; // Exemple de format pour o2switch
    $password = "votre_mot_de_passe_o2switch"; // Remplacer par votre vrai mot de passe
    $dbname = "delo5366_mangaheaven"; // Exemple de format pour o2switch
} else {
    // Configuration locale (WAMP)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mangaheaven";
}

// Afficher les erreurs en mode développement
if (!$is_o2switch) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Création de la connexion avec gestion d'erreurs
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Vérification de la connexion
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Configuration du jeu de caractères
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    // Gestion des erreurs
    if ($is_o2switch) {
        // En production, journaliser l'erreur mais afficher un message générique
        error_log("DB Connection Error: " . $e->getMessage());
        
        // Message d'erreur pour l'utilisateur
        die("Une erreur de connexion à la base de données s'est produite. Veuillez contacter l'administrateur.");
    } else {
        // En développement, afficher l'erreur complète
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}
?>
