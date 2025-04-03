<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../Page_Login/Login.php");
    exit;
}

// Include database connection
require_once('../../config/db_connect.php');

// Define variables and initialize with empty values
$username = $_SESSION["username"];
$new_username = $new_password = $confirm_password = $email = "";
$new_username_err = $new_password_err = $confirm_password_err = $email_err = "";
$success_msg = "";

// Get current user info
$sql = "SELECT email FROM users WHERE id = ?";
if($stmt = $conn->prepare($sql)){
    $stmt->bind_param("i", $_SESSION["id"]);
    if($stmt->execute()){
        $stmt->store_result();
        if($stmt->num_rows == 1){
            $stmt->bind_result($current_email);
            $stmt->fetch();
            $email = $current_email ?? "";
        }
    }
    $stmt->close();
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Check which form was submitted
    if(isset($_POST["update_username"])){
        // Validate new username
        if(empty(trim($_POST["new_username"]))){
            $new_username_err = "Veuillez entrer un nom d'utilisateur.";
        } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["new_username"]))){
            $new_username_err = "Le nom d'utilisateur ne peut contenir que des lettres, des chiffres et des underscores.";
        } else {
            // Check if username is the same as current
            if(trim($_POST["new_username"]) === $_SESSION["username"]){
                $new_username_err = "Le nouveau nom d'utilisateur doit être différent de l'actuel.";
            } else {
                // Check if username is already taken
                $sql = "SELECT id FROM users WHERE username = ?";
                if($stmt = $conn->prepare($sql)){
                    $stmt->bind_param("s", $param_username);
                    $param_username = trim($_POST["new_username"]);
                    
                    if($stmt->execute()){
                        $stmt->store_result();
                        if($stmt->num_rows == 1){
                            $new_username_err = "Ce nom d'utilisateur est déjà pris.";
                        } else{
                            $new_username = trim($_POST["new_username"]);
                        }
                    } else{
                        echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
                    }
                    $stmt->close();
                }
            }
        }
        
        // Check input errors before updating the database
        if(empty($new_username_err)){
            // Prepare an update statement
            $sql = "UPDATE users SET username = ? WHERE id = ?";
            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("si", $param_username, $param_id);
                $param_username = $new_username;
                $param_id = $_SESSION["id"];
                
                if($stmt->execute()){
                    // Update session variable
                    $_SESSION["username"] = $new_username;
                    $username = $new_username;
                    $success_msg = "Votre nom d'utilisateur a été mis à jour avec succès.";
                } else{
                    echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
                }
                $stmt->close();
            }
        }
    } elseif(isset($_POST["update_password"])) {
        // Validate new password
        if(empty(trim($_POST["new_password"]))){
            $new_password_err = "Veuillez entrer un nouveau mot de passe.";     
        } elseif(strlen(trim($_POST["new_password"])) < 6){
            $new_password_err = "Le mot de passe doit comporter au moins 6 caractères.";
        } else{
            $new_password = trim($_POST["new_password"]);
        }
        
        // Validate confirm password
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Veuillez confirmer le mot de passe.";
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($new_password_err) && ($new_password != $confirm_password)){
                $confirm_password_err = "Les mots de passe ne correspondent pas.";
            }
        }
        
        // Check input errors before updating the database
        if(empty($new_password_err) && empty($confirm_password_err)){
            // Prepare an update statement
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("si", $param_password, $param_id);
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];
                
                if($stmt->execute()){
                    $success_msg = "Votre mot de passe a été mis à jour avec succès.";
                } else{
                    echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
                }
                $stmt->close();
            }
        }
    } elseif(isset($_POST["update_email"])) {
        // Validate email
        if(!empty(trim($_POST["email"]))){
            if(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
                $email_err = "Format d'email invalide.";
            } else {
                // Check if email is already taken
                $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
                if($stmt = $conn->prepare($sql)){
                    $stmt->bind_param("si", $param_email, $param_id);
                    $param_email = trim($_POST["email"]);
                    $param_id = $_SESSION["id"];
                    
                    if($stmt->execute()){
                        $stmt->store_result();
                        if($stmt->num_rows > 0){
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
        
        // Check input errors before updating the database
        if(empty($email_err)){
            // Prepare an update statement
            $sql = "UPDATE users SET email = ? WHERE id = ?";
            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("si", $param_email, $param_id);
                $param_email = !empty($email) ? $email : null;
                $param_id = $_SESSION["id"];
                
                if($stmt->execute()){
                    $success_msg = "Votre adresse email a été mise à jour avec succès.";
                } else{
                    echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
                }
                $stmt->close();
            }
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - MangaHeaven</title>
    <link href="../../Styles.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../images/logo_MangaHeaven.png">
    <style>
        .account-section {
            max-width: 600px;
            margin: 0 auto 80px auto; /* Added bottom margin to avoid footer overlap */
            padding: 25px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }
        
        .update-form {
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
        }
        
        .update-form h3 {
            margin-top: 0;
            color: #333;
            font-size: 1.4em;
            margin-bottom: 20px;
        }
        
        .update-form form {
            display: flex;
            flex-direction: column;
        }
        
        .update-form label {
            margin: 10px 0 5px;
            font-weight: bold;
        }
        
        .update-form input {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        
        .update-form input:focus {
            border-color: #555;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        
        .update-form button {
            padding: 12px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin-top: 10px;
            transition: background-color 0.3s, transform 0.2s;
        }
        
        .update-form button:hover {
            background-color: #555;
            transform: translateY(-2px);
        }
        
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            padding: 8px;
            background-color: rgba(220, 53, 69, 0.1);
            border-radius: 4px;
            font-size: 0.9em;
        }
        
        .success-message {
            color: #28a745;
            margin-bottom: 20px;
            padding: 12px;
            background-color: #d4edda;
            border-radius: 5px;
            border-left: 4px solid #28a745;
            font-weight: bold;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .account-section {
                max-width: 90%;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- En-tête de la page -->
    <header>
        <h1>Mon Compte</h1>
        <nav>
            <ul>
                <li><a href="../Page_Accueil/Accueil.php">Accueil</a></li>
                <li><a href="../Page_Home/Home.php">Page d'accueil</a></li>
                <li><a href="../Page_Compte/Compte.php">Mon Compte</a></li>
                <li><a href="../logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    
    <!-- Contenu principal -->
    <main>
        <section class="account-section">
            <h2>Paramètres du compte - <?php echo htmlspecialchars($username); ?></h2>
            
            <?php 
            if(!empty($success_msg)){
                echo '<div class="success-message">' . $success_msg . '</div>';
            }
            ?>
            
            <div class="update-form">
                <h3>Modifier le nom d'utilisateur</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <label for="new_username">Nouveau nom d'utilisateur :</label>
                    <input type="text" id="new_username" name="new_username" value="<?php echo $new_username; ?>" required>
                    <?php if(!empty($new_username_err)){ echo '<div class="error-message">' . $new_username_err . '</div>'; } ?>
                    
                    <button type="submit" name="update_username">Mettre à jour</button>
                </form>
            </div>
            
            <div class="update-form">
                <h3>Modifier le mot de passe</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <label for="new_password">Nouveau mot de passe :</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <?php if(!empty($new_password_err)){ echo '<div class="error-message">' . $new_password_err . '</div>'; } ?>
                    
                    <label for="confirm_password">Confirmer le mot de passe :</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <?php if(!empty($confirm_password_err)){ echo '<div class="error-message">' . $confirm_password_err . '</div>'; } ?>
                    
                    <button type="submit" name="update_password">Mettre à jour</button>
                </form>
            </div>
            
            <div class="update-form" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
                <h3>Modifier l'adresse email</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <label for="email">Adresse email (facultatif) :</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                    <?php if(!empty($email_err)){ echo '<div class="error-message">' . $email_err . '</div>'; } ?>
                    
                    <button type="submit" name="update_email">Mettre à jour</button>
                </form>
            </div>
        </section>
    </main>
    
    <!-- Pied de page -->
    <footer>
        <p>&copy; 2025 Manga-Heaven. Tous droits réservés.</p>
    </footer>
</body>
</html>
