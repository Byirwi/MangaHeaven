<?php
// Initialize the session
session_start();

// Détruire toutes les variables de sessionession
$_SESSION = array();

// Supprimer le cookie de session si présentde session si présent
if (ini_get("session.use_cookies")) {on.use_cookies")) {
    $params = session_get_cookie_params();    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,() - 42000,
        $params["path"], $params["domain"],],
        $params["secure"], $params["httponly"]   $params["secure"], $params["httponly"]
    );  );
}}















































</html></body>    </div>        <a href="../HTML_PHP/Page_Home/Home.php" class="btn">Retour à l'accueil</a>        <p>Vous avez été déconnecté avec succès.</p>        <h1>Déconnexion réussie</h1>    <div class="logout-container"><body></head>    </style>        }            background-color: #555;        .btn:hover {        }            transition: background-color 0.3s;            margin-top: 20px;            border-radius: 5px;            text-decoration: none;            color: white;            background-color: #333;            padding: 10px 20px;            display: inline-block;        .btn {        }            box-shadow: 0 2px 10px rgba(0,0,0,0.1);            border-radius: 5px;            background-color: white;            padding: 30px;            text-align: center;            margin: 100px auto;            max-width: 500px;        .logout-container {    <style>    <link href="../Styles.css" rel="stylesheet">    <title>Déconnexion - MangaHeaven</title>    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <meta charset="UTF-8"><head><html lang="fr"><!DOCTYPE html>?>// Afficher une page de déconnexion avec lien explicitesession_destroy();// Détruire la session
// Détruire la session
session_destroy();

// Redirection avec un chemin absolu depuis la racine web
echo "<script>window.location.href = '/Projets/MangaHeaven/HTML_PHP/Page_Home/Home.php';</script>";
exit;
?>
