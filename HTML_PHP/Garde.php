<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur MangaHeaven - Votre univers manga</title>
    <link href="../Styles.css" rel="stylesheet">
</head>
<body>
    <!-- En-tête de la page -->
    <header>
        <h1>MangaHeaven</h1>
        <nav>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="Login.php">Connexion</a></li>
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
                    <a href="Accueil.php" class="cta-button primary">Explorer les mangas</a>
                    <a href="Login.php" class="cta-button secondary">Se connecter</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="../images/hero-collage.png" alt="Collection de mangas populaires">
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
                    <img src="../images/Naruto.jpg" alt="Naruto">
                    <h3>Naruto</h3>
                </div>
                <div class="manga-item">
                    <img src="../images/one_piece.jpg" alt="One Piece">
                    <h3>One Piece</h3>
                </div>
                <div class="manga-item">
                    <img src="../images/attack_on_titan.jpg" alt="Attack on Titan">
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
