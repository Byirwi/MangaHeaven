<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Métadonnées et lien vers le fichier CSS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - MangaHeaven</title>
    <link rel="stylesheet" href="../../Styles.css">
</head>
<body>
    <!-- En-tête de la page -->
    <header>
        <h1>Connexion à MangaHeaven</h1>
        <nav>
            <ul>
                <li><a href="../Page_Accueil/Accueil.php">Accueil</a></li>
                <!-- <li><a href="ListeMangas.html">Liste des Mangas</a></li> -->
                <li><a href="../Page_Login/Login.php">Connexion</a></li>
            </ul>
        </nav>
    </header>
    <!-- Contenu principal -->
    <main>
        <section class="login-section">
            <h2>Connectez-vous à votre compte</h2>
            <form class="login-form" action="../Page_Accueil/Accueil.php" method="post">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Connexion</button>
            </form>
        </section>
    </main>
    <!-- Pied de page -->
    <footer>
        <p>&copy; 2025 Manga-Heaven. Tous droits réservés.</p>
    </footer>
</body>
</html>