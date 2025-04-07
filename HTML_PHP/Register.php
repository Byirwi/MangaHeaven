<?php
/* ==========================================================================
   SYSTÈME D'INSCRIPTION - MangaHeaven
   ========================================================================== */

/* --------------------------------------------------------------------------
   INCLUSION DES DÉPENDANCES
   -------------------------------------------------------------------------- */
// Inclusion de la connexion à la base de données
require_once('../config/db_connect.php');

/* --------------------------------------------------------------------------
   INITIALISATION DES VARIABLES
   -------------------------------------------------------------------------- */
// Définition et initialisation des variables pour le formulaire
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";

/* --------------------------------------------------------------------------
   TRAITEMENT DU FORMULAIRE D'INSCRIPTION
   -------------------------------------------------------------------------- */
// Traitement lorsque le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validation du nom d'utilisateur
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer un nom d'utilisateur.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Le nom d'utilisateur ne peut contenir que des lettres, des chiffres et des underscores.";
    } else {
        // Vérification de l'unicité du nom d'utilisateur
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = $conn->prepare($sql)){
            // Liaison des paramètres
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);
            
            // Exécution de la requête
            if($stmt->execute()){
                // Stockage du résultat
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "Ce nom d'utilisateur est déjà pris.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
            }

            // Fermeture de la requête
            $stmt->close();
        }
    }
    
    // Validation de l'email (facultatif)
    if(!empty(trim($_POST["email"]))){
        // Validation uniquement si l'email est fourni
        if(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
            $email_err = "Format d'email invalide.";
        } else {
            // Vérification que l'email n'est pas déjà utilisé
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
    
    // Validation du mot de passe
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer un mot de passe.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Le mot de passe doit comporter au moins 6 caractères.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validation de la confirmation du mot de passe
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Veuillez confirmer le mot de passe.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Les mots de passe ne correspondent pas.";
        }
    }
    
    // Vérification des erreurs avant insertion dans la base de données
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){
        
        // Préparation de la requête d'insertion
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
         
        if($stmt = $conn->prepare($sql)){
            // Liaison des paramètres
            $stmt->bind_param("sss", $param_username, $param_password, $param_email);
            
            // Configuration des paramètres
            $param_username = $username;
            // Hachage du mot de passe pour sécurité
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            $param_email = $email ?: null; // Utiliser null si email est vide
            
            // Exécution de la requête
            if($stmt->execute()){
                // Redirection vers la page de connexion
                header("location: Login.php");
            } else{
                echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
            }

            // Fermeture de la requête
            $stmt->close();
        }
    }
    
    // Fermeture de la connexion
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr" class="dark-theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, viewport-fit=cover">
    <title>MangaHeaven</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../Public/Logo/favicon.png">
</head>
<body>
    <!-- En-tête de la page -->
    <header>
        <h1>Inscription à MangaHeaven</h1>
        <nav>
            <ul>
                <li><a href="Home.php">Home</a></li>
                <li><a href="Login.php">Connexion</a></li>
                <!-- Bouton de bascule du thème -->
                <li>
                    <button id="themeToggle" class="theme-toggle" aria-label="Changer de thème">
                        <span class="icon-moon"></span>
                        <span class="icon-sun"></span>
                    </button>
                </li>
            </ul>
        </nav>
    </header>
    
    <!-- Contenu principal -->
    <main>
        <section class="login-section">
            <h2>Créer un compte</h2>
            <!-- Formulaire d'inscription -->
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
                <p>Vous avez déjà un compte? <a href="Login.php">Connectez-vous ici</a></p>
            </form>
        </section>
    </main>
    
    <!-- Pied de page -->
    <footer>
        <p>&copy; 2025 Manga-Heaven. Tous droits réservés.</p>
    </footer>
</body>
</html>
