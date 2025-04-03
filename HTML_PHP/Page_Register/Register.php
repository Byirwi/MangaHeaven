<?php
// Include database connection
require_once('../../config/db_connect.php');

// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer un nom d'utilisateur.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Le nom d'utilisateur ne peut contenir que des lettres, des chiffres et des underscores.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "Ce nom d'utilisateur est déjà pris.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Validate email - made optional
    if(!empty(trim($_POST["email"]))){
        // Only validate if email is provided
        if(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
            $email_err = "Format d'email invalide.";
        } else {
            // Check if email already exists
            $sql = "SELECT id FROM users WHERE email = ?";
            
            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("s", $param_email);
                $param_email = trim($_POST["email"]);
                
                if($stmt->execute()){
                    $stmt->store_result();
                    
                    if($stmt->num_rows == 1){
                        $email_err = "Cette adresse email est déjà utilisée.";
                    } else{
                        $email = trim($_POST["email"]);
                    }
                } else{
                    echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
                }
                
                $stmt->close();
            }
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer un mot de passe.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Le mot de passe doit comporter au moins 6 caractères.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Veuillez confirmer le mot de passe.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Les mots de passe ne correspondent pas.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
         
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_username, $param_password, $param_email);
            
            // Set parameters
            $param_username = $username;
            // Create a password hash
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            $param_email = $email ?: null; // Use null if email is empty
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: ../Page_Login/Login.php");
            } else{
                echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>MangaHeaven</title>
    <link rel="stylesheet" href="../../Styles.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../Ressource/Logo/favicon.png">
</head>
<body>
    <!-- En-tête de la page -->
    <header>
        <h1>Inscription à MangaHeaven</h1>
        <nav>
            <ul>
                <li><a href="../Page_Home/Home.php">Home</a></li>
                <li><a href="../Page_Login/Login.php">Connexion</a></li>
            </ul>
        </nav>
    </header>
    
    <!-- Contenu principal -->
    <main>
        <section class="login-section">
            <h2>Créer un compte</h2>
            <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                <?php if(!empty($username_err)){ echo '<div class="error-message">' . $username_err . '</div>'; } ?>
                
                <label for="email">Email (facultatif) :</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                <?php if(!empty($email_err)){ echo '<div class="error-message">' . $email_err . '</div>'; } ?>
                
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                <?php if(!empty($password_err)){ echo '<div class="error-message">' . $password_err . '</div>'; } ?>
                
                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <?php if(!empty($confirm_password_err)){ echo '<div class="error-message">' . $confirm_password_err . '</div>'; } ?>
                
                <button type="submit">S'inscrire</button>
                <p>Vous avez déjà un compte? <a href="../Page_Login/Login.php">Connectez-vous ici</a>.</p>
            </form>
        </section>
    </main>
    
    <!-- Pied de page -->
    <footer>
        <p>&copy; 2025 Manga-Heaven. Tous droits réservés.</p>
    </footer>
</body>
</html>
