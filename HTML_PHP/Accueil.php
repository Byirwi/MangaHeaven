<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
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
    <link rel="icon" type="image/png" href="../Public/Logo/favicon.png?v=<?php echo time(); ?>">
</head>
<body>
    <!-- En-tête de la page -->
    <header>
        <h1>Bienvenue sur MangaHeaven, <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
        <nav>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <!-- <li><a href="ListeMangas.php">Liste des Mangas</a></li> -->
                <li><a href="Home.php">Home</a></li>
                <li><a href="Compte.php">Mon Compte</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <!-- Contenu principal -->
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
                    <img src="../Public/images/arifureta.jpg" alt="Arifureta">
                    <h3>Arifureta</h3>
                </div>
            </div>
        </section>
    </main>
    <!-- Pied de page -->
    <footer>
        <p>&copy; 2025 Lecture de Manga en Ligne. Tous droits réservés.</p>
    </footer>
</body>
</html>
