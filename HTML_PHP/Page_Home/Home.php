<?php
// Initialize the session
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MangaHeaven</title>
    <link href="../../Styles.css" rel="stylesheet">
    <!-- Favicon - multiple formats for better compatibility -->
    <link rel="icon" type="image/x-icon" href="../../favicon.ico">
    <link rel="icon" type="image/png" href="../../favicon.png">
    <link rel="apple-touch-icon" href="../../favicon.png">
    <!-- Force favicon refresh with this meta tag -->
    <meta name="theme-color" content="#ffffff">
</head>
<body>
    <!-- En-tête de la page -->
    <header>
        <h1>MangaHeaven</h1>
        <nav>
            <ul>
                <?php
                // If user is logged in, show Accueil, Mon Compte and Logout links, otherwise show Login and Register
                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                    echo '<li><a href="../Page_Accueil/Accueil.php">Accueil</a></li>';
                    echo '<li><a href="../Page_Compte/Compte.php">Mon Compte</a></li>';
                    echo '<li><a href="../logout.php">Déconnexion</a></li>';
                } else {
                    echo '<li><a href="../Page_Login/Login.php">Connexion</a></li>';
                    echo '<li><a href="../Page_Register/Register.php">Inscription</a></li>';
                }
                ?>
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
                    <?php
                    // If user is logged in, direct to accueil, otherwise to login/register
                    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                        echo '<a href="../Page_Accueil/Accueil.php" class="cta-button primary">Explorer les mangas</a>';
                    } else {
                        echo '<a href="../Page_Login/Login.php" class="cta-button primary">Explorer les mangas</a>';
                        echo '<a href="../Page_Register/Register.php" class="cta-button secondary">S\'inscrire</a>';
                    }
                    ?>
                </div>
            </div>
            <div class="hero-image">
                <img src="../../images/hero-collage.png" alt="Collection de mangas populaires">
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
                    <img src="../../images/Naruto.jpg" alt="Naruto">
                    <h3>Naruto</h3>
                </div>
                <div class="manga-item">
                    <img src="../../images/one_piece.jpg" alt="One Piece">
                    <h3>One Piece</h3>
                </div>
                <div class="manga-item">
                    <img src="../../images/attack_on_titan.jpg" alt="Attack on Titan">
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
