<?php
// Initialize the session
session_start();

// For debugging - uncomment to see session contents
// echo '<pre>'; print_r($_SESSION); echo '</pre>';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, viewport-fit=cover">
    <title>MangaHeaven</title>
    <link href="../CSS/Styles.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../Public/Logo/favicon.png">
    <!-- Force favicon refresh with this meta tag -->
    <meta name="theme-color" content="#222222">
    
    <!-- Add touch highlight color for better mobile feedback -->
    <meta name="msapplication-tap-highlight" content="#ff5252">
</head>
<body>
    <!-- En-tête de la page -->
    <header>
        <h1>Home</h1>
        <nav>
            <ul>
                <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                    <li><a href="Accueil.php">Accueil</a></li>
                    <li><a href="Home.php">Home</a></li>
                    <li><a href="Compte.php">Mon Compte</a></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="Login.php">Connexion</a></li>
                    <li><a href="Register.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Contenu principal -->
    <main>
        <section class="landing-hero">
            <div class="hero-content">
                <h2>Plongez dans l'univers des mangas</h2>
                <p>Découvrez, lisez et suivez vos mangas préférés en ligne.</p>
                <div class="cta-buttons">
                    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                        <a href="Accueil.php" class="cta-button primary">Explorer les mangas</a>
                        <a href="Compte.php" class="cta-button secondary">Mon compte</a>
                    <?php else: ?>
                        <a href="Login.php" class="cta-button primary">Explorer les mangas</a>
                        <a href="Register.php" class="cta-button secondary">S'inscrire</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-image">
                <img src="../Public/images/hero-collage.png" alt="Collection de mangas populaires">
            </div>
        </section>

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
            <div class="feature-item">
                <div class="feature-icon">🌙</div>
                <h3>Mode nuit</h3>
                <p>Une lecture confortable, même dans l'obscurité</p>
            </div>
        </section>

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
</body>
</html>