<?php
/* ==========================================================================
   CONFIGURATION BASE DE DONNÉES - MangaHeaven
   ========================================================================== */
// Fichier de configuration pour la connexion à la base de données
// Ce fichier devrait être exclu du contrôle de version

/* --------------------------------------------------------------------------
   DÉTECTION DE L'ENVIRONNEMENT & CONFIGURATION
   -------------------------------------------------------------------------- */
// Détection automatique de l'environnement (production vs local)
if (strpos($_SERVER['HTTP_HOST'] ?? '', 'ldpa-tech.fr') !== false) {
    // Paramètres de la base de données en production
    define('DB_HOST', 'localhost');
    define('DB_USER', 'delo5366_MangaHeaven_Account');
    define('DB_PASS', 'm@Vw6XndBrbumVaU.-e!QqyV');
    define('DB_NAME', 'delo5366_mangaheaven');
    define('DB_PORT', 3306);
} else {
    // Paramètres de la base de données en développement local
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'mangaheaven');
    define('DB_PORT', 3306);
}

/* --------------------------------------------------------------------------
   PARAMÈTRES DE SÉCURITÉ SSL/TLS
   -------------------------------------------------------------------------- */
// Activation du SSL uniquement en production pour sécuriser les transferts
define('DB_SSL', strpos($_SERVER['HTTP_HOST'] ?? '', 'ldpa-tech.fr') !== false);
?>
