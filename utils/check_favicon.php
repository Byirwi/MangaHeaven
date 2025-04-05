<?php
// Script pour vérifier et copier les favicons si nécessaire

// Définir les chemins
$basePath = dirname(__DIR__); // Chemin racine du projet

// Créer le dossier Public/images s'il n'existe pas
$publicPath = $basePath . '/Public';
$imagesPath = $publicPath . '/images';

if (!file_exists($publicPath)) {
    if (mkdir($publicPath, 0755)) {
        echo "✅ Dossier Public créé<br>";
    } else {
        echo "❌ Impossible de créer le dossier Public<br>";
    }
}

if (!file_exists($imagesPath)) {
    if (mkdir($imagesPath, 0755)) {
        echo "✅ Dossier Public/images créé<br>";
    } else {
        echo "❌ Impossible de créer le dossier Public/images<br>";
    }
}

// Vérifier les fichiers favicon
$oldFaviconPng = $basePath . '/favicon.png';
$newFaviconPng = $imagesPath . '/favicon.png';

if (file_exists($oldFaviconPng) && !file_exists($newFaviconPng)) {
    if (copy($oldFaviconPng, $newFaviconPng)) {
        echo "✅ favicon.png copié vers Public/images/<br>";
    } else {
        echo "❌ Impossible de copier favicon.png<br>";
    }
} else if (!file_exists($newFaviconPng) && !file_exists($oldFaviconPng)) {
    echo "⚠️ Aucun favicon.png trouvé. Exécutez favicon.php pour le générer.<br>";
} else if (file_exists($newFaviconPng)) {
    echo "✅ favicon.png existe déjà dans Public/images/<br>";
}

// Vérifier le logo
$oldLogoPath = $basePath . '/images/logo_MangaHeaven.png';
$newLogoPath = $imagesPath . '/logo_MangaHeaven.png';

if (file_exists($oldLogoPath) && !file_exists($newLogoPath)) {
    if (copy($oldLogoPath, $newLogoPath)) {
        echo "✅ Logo copié vers Public/images/<br>";
    } else {
        echo "❌ Impossible de copier le logo<br>";
    }
} else if (!file_exists($newLogoPath) && !file_exists($oldLogoPath)) {
    echo "⚠️ Aucun logo trouvé. Placez logo_MangaHeaven.png dans Public/images/<br>";
} else if (file_exists($newLogoPath)) {
    echo "✅ Logo existe déjà dans Public/images/<br>";
}

// Vérifier les permissions
if (file_exists($newFaviconPng)) {
    if (is_readable($newFaviconPng)) {
        echo "✅ favicon.png est lisible<br>";
    } else {
        echo "❌ favicon.png existe mais n'est pas lisible (problème de permissions)<br>";
    }
    
    echo "Information du fichier favicon.png:<br>";
    echo "- Taille: " . filesize($newFaviconPng) . " octets<br>";
    echo "- Dernière modification: " . date("Y-m-d H:i:s", filemtime($newFaviconPng)) . "<br>";
}

echo "<p>Pour forcer le rechargement du favicon, videz le cache de votre navigateur ou utilisez Ctrl+F5.</p>";
echo "<p><a href='../HTML_PHP/Home.php'>Retourner à l'accueil</a></p>";
echo "<p><a href='../favicon.php'>Exécuter favicon.php pour régénérer les favicons</a></p>";
?>
