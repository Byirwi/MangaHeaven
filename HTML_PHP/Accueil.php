<?php
/* ==========================================================================
   PAGE D'ACCUEIL UTILISATEUR - MangaHeaven
   ========================================================================== */

/* --------------------------------------------------------------------------
   INITIALISATION & VÉRIFICATION DE SESSION
   -------------------------------------------------------------------------- */
// Démarrage de la session
session_start();

// Vérification si l'utilisateur est connecté, sinon redirection
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: Login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>MangaHeaven</title>
    <link href="../CSS/Styles.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../Public/Logo/favicon.png">
</head>
<body>
    <!-- En-tête de la page avec le nom de l'utilisateur -->
    <header>
        <h1>Bienvenue sur MangaHeaven, <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
        <nav>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <!-- <li><a href="ListeMangas.php">Liste des Mangas</a></li> -->
                <li><a href="Home.php">Home</a></li>
                <li><a href="Compte.php">Mon Compte</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
                <!-- Bouton de bascule du thème -->
                <li>
                    <button id="themeToggle" class="theme-toggle" aria-label="Changer de thème">
                        <span class="icon-moon">🌙</span>
                        <span class="icon-sun">☀️</span>
                    </button>
                </li>
            </ul>
        </nav>
    </header>
    
    <!-- Contenu principal avec liste de mangas populaires -->
    <main>
        <section class="manga-list">
            <h2>Mangas Populaires</h2>
            <div class="manga-container">
                <!-- Liste des mangas populaires -->
                <div class="manga-item">
                    <img src="../Public/images/Naruto.jpg" alt="Naruto">
                    <h3>Naruto</h3>
                </div>
                <div class="manga-item">
                    <img src="../Public/images/one_piece.jpg" alt="One Piece">
                    <h3>One Piece</h3>
                </div>
                <div class="manga-item">
                    <img src="../Public/images/attack_on_titan.jpg" alt="Attack on Titan">
                    <h3>Attack on Titan</h3>
                </div>
                <div class="manga-item">
                    <img src="../Public/images/my_hero_academia.jpg" alt="My Hero Academia">
                    <h3>My Hero Academia</h3>
                </div>
                <div class="manga-item">
                    <img src="../Public/images/Solo_Leveling.png" alt="Solo Leveling">
                    <h3>Solo Leveling</h3>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Pied de page -->
    <footer>
        <p>&copy; 2025 Lecture de Manga en Ligne. Tous droits réservés.</p>
    </footer>
    
    <!-- Scripts JavaScript -->
    <script src="../JavaScript/Theme.js"></script>
    <script>
        // Initialiser le bouton de thème
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            
            themeToggle.addEventListener('click', function() {
                // Utiliser le ThemeManager pour basculer entre les thèmes
                window.themeManager.toggleTheme();
            });
        });
    </script>
</body>
</html>
