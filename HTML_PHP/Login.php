<?php
/* ==========================================================================
   SYSTÈME DE CONNEXION - MangaHeaven
   ========================================================================== */

/* --------------------------------------------------------------------------
   INITIALISATION & VÉRIFICATION DE SESSION
   -------------------------------------------------------------------------- */
// Démarrage de la session pour gérer les variables de session
session_start();

// Redirection si l'utilisateur est déjà connecté
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: Accueil.php');
    exit;
}

/* --------------------------------------------------------------------------
   INCLUSION DES DÉPENDANCES
   -------------------------------------------------------------------------- */
// Inclusion de la connexion à la base de données
require_once('../config/db_connect.php');

/* --------------------------------------------------------------------------
   INITIALISATION DES VARIABLES
   -------------------------------------------------------------------------- */
// Définition et initialisation des variables pour le formulaire
$username = $password = "";
$username_err = $password_err = $login_err = "";

/* --------------------------------------------------------------------------
   TRAITEMENT DU FORMULAIRE DE CONNEXION
   -------------------------------------------------------------------------- */
// Traitement des données du formulaire lors de la soumission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Vérification du nom d'utilisateur
    if (empty(trim($_POST["username"]))) {
        $username_err = "Veuillez entrer votre nom d'utilisateur.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Vérification du mot de passe
    if (empty(trim($_POST["password"]))) {
        $password_err = "Veuillez entrer votre mot de passe.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validation des identifiants si aucune erreur
    if (empty($username_err) && empty($password_err)) {
        // Préparation de la requête SQL pour vérifier l'utilisateur
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Association des paramètres avec la requête préparée
            $stmt->bind_param("s", $param_username);
            $param_username = $username;
            
            // Exécution de la requête
            if ($stmt->execute()) {
                // Stockage du résultat
                $stmt->store_result();
                
                // Vérification de l'existence du nom d'utilisateur
                if ($stmt->num_rows == 1) {
                    // Récupération des variables de résultat
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        // Vérification du mot de passe
                        if (password_verify($password, $hashed_password)) {
                            // Initialisation d'une nouvelle session
                            session_start();
                            
                            // Stockage des données dans les variables de session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirection vers la page d'accueil
                            header("location: Accueil.php");
                        } else {
                            // Message d'erreur générique pour la sécurité
                            $login_err = "Nom d'utilisateur ou mot de passe invalide.";
                        }
                    }
                } else {
                    // Message d'erreur générique pour la sécurité
                    $login_err = "Nom d'utilisateur ou mot de passe invalide.";
                }
            } else {
                echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
            }

            // Fermeture de la requête préparée
            $stmt->close();
        }
    }
    
    // Fermeture de la connexion à la base de données
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr" class="dark-theme">
<head>
    <!-- Métadonnées et lien vers le fichier CSS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>MangaHeaven</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../Public/Logo/favicon.png">
</head>
<body>
    <!-- En-tête de la page -->
    <header>
        <h1>Connexion à MangaHeaven</h1>
        <nav>
            <ul>
                <li><a href="Home.php">Home</a></li>
                <li><a href="Login.php">Connexion</a></li>
                <li><a href="Register.php">Inscription</a></li>
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
            <h2>Connectez-vous à votre compte</h2>
            
            <?php 
            // Affichage du message d'erreur de connexion si nécessaire
            if(!empty($login_err)){
                echo '<div class="error-message">' . $login_err . '</div>';
            }        
            ?>
            
            <!-- Formulaire de connexion -->
            <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                <?php if(!empty($username_err)){ echo '<div class="error-message">' . $username_err . '</div>'; } ?>
                
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                <?php if(!empty($password_err)){ echo '<div class="error-message">' . $password_err . '</div>'; } ?>
                
                <button type="submit">Connexion</button>
                <p>Vous n'avez pas de compte? <a href="Register.php">Inscrivez-vous maintenant</a></p>
            </form>
        </section>
    </main>
    
    <!-- Pied de page -->
    <footer>
        <p>&copy; 2025 Manga-Heaven. Tous droits réservés.</p>
    </footer>
</body>
</html>