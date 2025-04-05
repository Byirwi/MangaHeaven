<?php
/* ==========================================================================
   CONNEXION ET FONCTIONS DATABASE - MangaHeaven
   ========================================================================== */

/* --------------------------------------------------------------------------
   CHARGEMENT DE LA CONFIGURATION
   -------------------------------------------------------------------------- */
// Inclusion des paramètres de connexion à la base de données
require_once __DIR__ . '/db_config.php';

/* --------------------------------------------------------------------------
   ÉTABLISSEMENT DE LA CONNEXION MYSQLI
   -------------------------------------------------------------------------- */
try {
    // Initialisation de la connexion MySQLi
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    // Vérification de la connexion
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Configuration SSL si activée (sécurité supplémentaire)
    if (defined('DB_SSL') && DB_SSL) {
        // Activation de la connexion SSL
        $conn->ssl_set(NULL, NULL, '/path/to/ca.pem', NULL, NULL); // À mettre à jour avec le vrai chemin
    }
    
    // Définition du jeu de caractères pour gérer correctement les caractères spéciaux
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // Gestion des erreurs adaptée au contexte (production vs développement)
    if (strpos($_SERVER['HTTP_HOST'] ?? '', 'ldpa-tech.fr') !== false) {
        // En production, masquer les détails techniques
        error_log("Database connection error: " . $e->getMessage());
        die("Une erreur de connexion à la base de données s'est produite. Veuillez contacter l'administrateur.");
    } else {
        // En développement, montrer les détails pour faciliter le débogage
        die("Database connection error: " . $e->getMessage());
    }
}

/* --------------------------------------------------------------------------
   FONCTION UTILITAIRE POUR REQUÊTES PRÉPARÉES
   -------------------------------------------------------------------------- */
/**
 * Fonction d'exécution sécurisée de requêtes préparées avec MySQLi
 * 
 * @param string $sql La requête SQL à préparer
 * @param array $params Tableau de paramètres à lier (format: ['types', param1, param2, ...])
 * @return mysqli_stmt|bool La requête exécutée ou le résultat
 */
function dbExecute($sql, $params = []) {
    global $conn;
    try {
        // Préparation de la requête
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }
        
        // Liaison des paramètres si nécessaire
        if (!empty($params)) {
            $types = '';
            $bindParams = [];
            
            // Pour MySQLi, séparation des types et des valeurs
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i'; // Integer
                } elseif (is_float($param)) {
                    $types .= 'd'; // Double
                } elseif (is_string($param)) {
                    $types .= 's'; // String
                } else {
                    $types .= 'b'; // Blob
                }
                $bindParams[] = $param;
            }
            
            // Préparation des paramètres pour bind_param
            $bindParamsRef = [];
            $bindParamsRef[] = $types;
            
            // Création des références pour bind_param
            foreach ($bindParams as $key => $value) {
                $bindParamsRef[] = &$bindParams[$key];
            }
            
            // Appel de bind_param avec le tableau de références décompressé
            call_user_func_array([$stmt, 'bind_param'], $bindParamsRef);
        }
        
        // Exécution de la requête
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }
        
        return $stmt;
    } catch (Exception $e) {
        // Journalisation de l'erreur
        error_log("SQL Error: " . $e->getMessage() . " in query: " . $sql);
        
        // Gestion différente selon l'environnement
        if (strpos($_SERVER['HTTP_HOST'] ?? '', 'ldpa-tech.fr') !== false) {
            throw new Exception("Une erreur de base de données s'est produite.");
        } else {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
?>
