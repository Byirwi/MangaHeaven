<?php
// Load configuration (credentials)
require_once __DIR__ . '/db_config.php';

// Create connection with MySQLi and improved error handling
try {
    // Initialize MySQLi connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Configure SSL if enabled
    if (defined('DB_SSL') && DB_SSL) {
        // Enable SSL connection
        $conn->ssl_set(NULL, NULL, '/path/to/ca.pem', NULL, NULL); // Update with real path if available
    }
    
    // Set charset to ensure proper handling of special characters
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // More user-friendly error handling
    if (strpos($_SERVER['HTTP_HOST'] ?? '', 'ldpa-tech.fr') !== false) {
        // On production, don't show detailed errors
        error_log("Database connection error: " . $e->getMessage());
        die("Une erreur de connexion à la base de données s'est produite. Veuillez contacter l'administrateur.");
    } else {
        // In development, show detailed errors
        die("Database connection error: " . $e->getMessage());
    }
}

/**
 * Helper function to execute prepared statements securely with MySQLi
 * 
 * @param string $sql The SQL statement to prepare
 * @param array $params Array of parameters to bind (format: ['types', param1, param2, ...])
 * @return mysqli_stmt|bool The executed statement or result
 */
function dbExecute($sql, $params = []) {
    global $conn;
    try {
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }
        
        // If we have parameters, bind them
        if (!empty($params)) {
            $types = '';
            $bindParams = [];
            
            // For MySQLi we need to separate the types from the values
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } else {
                    $types .= 'b';
                }
                $bindParams[] = $param;
            }
            
            // First parameter is the types string, followed by references to each value
            $bindParamsRef = [];
            $bindParamsRef[] = $types;
            
            // Create references for bind_param
            foreach ($bindParams as $key => $value) {
                $bindParamsRef[] = &$bindParams[$key];
            }
            
            // Call bind_param with unpacked array of references
            call_user_func_array([$stmt, 'bind_param'], $bindParamsRef);
        }
        
        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }
        
        return $stmt;
    } catch (Exception $e) {
        // Log the error
        error_log("SQL Error: " . $e->getMessage() . " in query: " . $sql);
        
        // In production, hide details
        if (strpos($_SERVER['HTTP_HOST'] ?? '', 'ldpa-tech.fr') !== false) {
            throw new Exception("Une erreur de base de données s'est produite.");
        } else {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
?>
