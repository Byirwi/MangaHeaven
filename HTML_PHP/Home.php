<?php
/* ==========================================================================
   PAGE D'ACCUEIL PUBLIQUE - MangaHeaven
   ========================================================================== */

/* --------------------------------------------------------------------------
   INITIALISATION DE SESSION
   -------------------------------------------------------------------------- */
// Démarrage de la session pour vérifier si l'utilisateur est connecté
session_start();

// Pour le débogage - décommenter pour voir le contenu de la session
// echo '<pre>'; print_r($_SESSION); echo '</pre>';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, viewport-fit=cover">
    <title>MangaHeaven</title>
    <link href="../CSS/Styles.css" rel="stylesheet">
    <!-- Favicon et métadonnées d'optimisation mobile -->
    <link rel="icon" type="image/png" href="../Public/Logo/favicon.png">
    <meta name="theme-color" content="#222222">
    <meta name="msapplication-tap-highlight" content="#ff5252">
</head>
<body>
    <!-- En-tête avec navigation adaptative selon la connexion -->
    <header>
        <h1>Home</h1>
        <nav>
            <ul>
                <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                    <!-- Menu pour utilisateurs connectés -->
                    <li><a href="Accueil.php">Accueil</a></li>
                    <li><a href="Home.php">Home</a></li>
                    <li><a href="Compte.php">Mon Compte</a></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <!-- Menu pour visiteurs -->
                    <li><a href="Login.php">Connexion</a></li>
                    <li><a href="Register.php">Inscription</a></li>
                <?php endif; ?>
                <!-- Bouton de bascule du thème retiré de la navigation -->
            </ul>
        </nav>
    </header>

    <!-- Contenu principal -->
    <main>
        <!-- Section Hero avec appel à l'action -->
        <section class="landing-hero">
            <div class="hero-content">
                <h2>Plongez dans l'univers des mangas</h2>
                <p>Découvrez, lisez et suivez vos mangas préférés en ligne.</p>
                <div class="cta-buttons">
                    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                        <!-- Boutons pour utilisateurs connectés -->
                        <a href="Accueil.php" class="cta-button primary">Explorer les mangas</a>
                        <a href="Compte.php" class="cta-button secondary">Mon compte</a>
                    <?php else: ?>
                        <!-- Boutons pour visiteurs -->
                        <a href="Login.php" class="cta-button primary">Explorer les mangas</a>
                        <a href="Register.php" class="cta-button secondary">S'inscrire</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-image">
                <img src="../Public/images/hero-collage.png" alt="Collection de mangas populaires">
            </div>
        </section>

        <!-- Section des fonctionnalités principales -->
        <section class="features">
            <div class="feature-item">
                <div class="feature-icon">📚</div>
                <h3>Bibliothèque immense</h3>
                <p>Des milliers de mangas à découvrir dans tous les genres</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🔖</div>
                <h3>Marque-pages</h3>
                <p>Reprenez votre lecture exactement où vous l'avez laissée</p>
            </div>
            <!-- Transformer la section Mode nuit en bouton de toggle de thème interactif -->
            <div class="feature-item theme-toggle-feature" id="themeToggle">
                <div class="feature-icon">
                    <span class="icon-moon"></span>
                    <span class="icon-sun"></span>
                </div>
                <h3>Mode nuit</h3>
                <p>Cliquez pour basculer entre thème clair et sombre</p>
            </div>
        </section>

        <!-- Section des mangas tendance -->
        <section class="trending">
            <h2>Tendances du moment</h2>
            <div class="manga-container">
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
            </div>
        </section>
    </main>

    <!-- Pied de page -->
    <footer>
        <p>&copy; 2025 Manga-Heaven. Tous droits réservés.</p>
    </footer>

    <!-- Scripts JavaScript -->
    <script src="../JavaScript/Theme.js"></script>
    <script>
        // Initialiser le bouton de thème
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            
            // Mise à jour de l'apparence initiale du bouton
            updateThemeButtonAppearance();
            
            themeToggle.addEventListener('click', function() {
                // Utiliser le ThemeManager pour basculer entre les thèmes
                window.themeManager.toggleTheme();
                
                // Mettre à jour l'apparence du bouton après changement
                updateThemeButtonAppearance();
            });
            
            // Fonction pour mettre à jour l'apparence du bouton selon le thème
            function updateThemeButtonAppearance() {
                const isDarkTheme = document.documentElement.classList.contains('dark-theme');
                const modeText = document.querySelector('#themeToggle h3');
                
                if (isDarkTheme) {
                    modeText.textContent = 'Mode jour';
                } else {
                    modeText.textContent = 'Mode nuit';
                }
            }
            
            // Écouter les changements de thème pour mettre à jour l'apparence
            document.addEventListener('themeChanged', function(e) {
                updateThemeButtonAppearance();
            });
        });
    </script>
</body>
</html>