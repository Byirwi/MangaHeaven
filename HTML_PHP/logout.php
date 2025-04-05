<?php
/* ==========================================================================
   DÉCONNEXION UTILISATEUR - MangaHeaven
   ========================================================================== */

/* --------------------------------------------------------------------------
   GESTION DE LA SESSION
   -------------------------------------------------------------------------- */
// Initialisation de la session pour accéder aux variables
session_start();

// Effacement de toutes les variables de session
$_SESSION = array();

/* --------------------------------------------------------------------------
   SUPPRESSION DU COOKIE DE SESSION
   -------------------------------------------------------------------------- */
// Si l'utilisation de cookies de session est activée, suppression du cookie
if (ini_get("session.use_cookies")) {
    // Récupération des paramètres du cookie de session
    $params = session_get_cookie_params();
    
    // Définition d'un cookie de session expiré pour le forcer à être supprimé
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

/* --------------------------------------------------------------------------
   DESTRUCTION DE LA SESSION ET REDIRECTION
   -------------------------------------------------------------------------- */
// Destruction complète de la session
session_destroy();

// Redirection vers la page d'accueil
header("location: Home.php");
exit;
?>
