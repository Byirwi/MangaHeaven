<?php
// Script de diagnostic et réparation pour les problèmes de favicon

echo "<h1>Diagnostic du favicon</h1>";

// Obtenir le chemin racine réel du projet
$project_root = realpath(dirname(__DIR__));
echo "<p>Chemin racine du projet: <code>" . htmlspecialchars($project_root) . "</code></p>";

// Créer les dossiers requis si nécessaire
$public_dir = $project_root . DIRECTORY_SEPARATOR . 'Public';
$logo_dir = $public_dir . DIRECTORY_SEPARATOR . 'Logo';
$images_dir = $public_dir . DIRECTORY_SEPARATOR . 'images';

if (!file_exists($public_dir)) {
    if (mkdir($public_dir, 0755)) {
        echo "<p>✅ Dossier Public créé</p>";
    } else {
        echo "<p>❌ Impossible de créer le dossier Public</p>";
    }
}

if (!file_exists($logo_dir)) {
    if (mkdir($logo_dir, 0755)) {
        echo "<p>✅ Dossier Public/Logo créé</p>";
    } else {
        echo "<p>❌ Impossible de créer le dossier Public/Logo</p>";
    }
}

if (!file_exists($images_dir)) {
    if (mkdir($images_dir, 0755)) {
        echo "<p>✅ Dossier Public/images créé</p>";
    } else {
        echo "<p>❌ Impossible de créer le dossier Public/images</p>";
    }
}

// Créer un favicon temporaire si aucun logo n'est trouvé
function createTemporaryLogo($path) {
    // Créer une image simple de 100x100 pixels avec "MH" comme texte
    $img = imagecreatetruecolor(100, 100);
    $bg = imagecolorallocate($img, 40, 40, 40);
    $text_color = imagecolorallocate($img, 255, 100, 100);
    
    // Remplir l'arrière-plan
    imagefilledrectangle($img, 0, 0, 99, 99, $bg);
    
    // Ajouter le texte "MH"
    imagestring($img, 5, 35, 40, "MH", $text_color);
    
    // Enregistrer l'image
    imagepng($img, $path);
    imagedestroy($img);
    
    return true;
}

// Vérifier l'existence du logo et en créer un temporaire si nécessaire
$logo_found = false;
$possible_logo_paths = [
    $project_root . DIRECTORY_SEPARATOR . 'Public' . DIRECTORY_SEPARATOR . 'Logo' . DIRECTORY_SEPARATOR . 'logo_MangaHeaven.png',
    $project_root . DIRECTORY_SEPARATOR . 'Public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'logo_MangaHeaven.png',
    $project_root . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'logo_MangaHeaven.png',
    $project_root . DIRECTORY_SEPARATOR . 'Ressource' . DIRECTORY_SEPARATOR . 'Logo' . DIRECTORY_SEPARATOR . 'logo_MangaHeaven.png',
];

echo "<h2>Recherche du logo</h2>";
echo "<ul>";

foreach ($possible_logo_paths as $path) {
    if (file_exists($path)) {
        echo "<li>✅ Logo trouvé à: <code>" . htmlspecialchars($path) . "</code></li>";
        $logo_found = true;
        $logo_path = $path;
        break;
    } else {
        echo "<li>❌ Logo non trouvé à: <code>" . htmlspecialchars($path) . "</code></li>";
    }
}

echo "</ul>";

if (!$logo_found) {
    echo "<p>⚠️ Aucun logo trouvé. Création d'un logo temporaire...</p>";
    $logo_path = $logo_dir . DIRECTORY_SEPARATOR . 'logo_MangaHeaven.png';
    if (createTemporaryLogo($logo_path)) {
        echo "<p>✅ Logo temporaire créé à: <code>" . htmlspecialchars($logo_path) . "</code></p>";
    } else {
        echo "<p>❌ Impossible de créer un logo temporaire</p>";
        die("Impossible de continuer sans logo.");
    }
}

// Générer le favicon à partir du logo
$favicon_png = $logo_dir . DIRECTORY_SEPARATOR . 'favicon.png';
$favicon_ico = $logo_dir . DIRECTORY_SEPARATOR . 'favicon.ico';

try {
    if (extension_loaded('gd')) {
        // Load the original image
        $source = imagecreatefrompng($logo_path);
        if (!$source) {
            die("Impossible de créer l'image à partir du PNG. Vérifiez que le fichier est une image PNG valide.");
        }
        
        // Get original dimensions
        $width = imagesx($source);
        $height = imagesy($source);
        
        // Create a square favicon (32x32 pixels)
        $favicon = imagecreatetruecolor(32, 32);
        
        // Preserve transparency
        imagealphablending($favicon, false);
        imagesavealpha($favicon, true);
        $transparent = imagecolorallocatealpha($favicon, 255, 255, 255, 127);
        imagefilledrectangle($favicon, 0, 0, 32, 32, $transparent);
        
        // Resize the image
        imagecopyresampled($favicon, $source, 0, 0, 0, 0, 32, 32, $width, $height);
        
        // Save the favicon
        imagepng($favicon, $favicon_png);
        
        // Also create an ICO version if possible
        if (function_exists('imagewbmp')) {
            // Note: ICO creation is complex, this is a simple approach
            // For better results, consider using a dedicated library or tool
            imagewbmp($favicon, $favicon_ico);
        }
        
        echo "Favicon créé avec succès à $favicon_png<br>";
        if (file_exists($favicon_ico)) {
            echo "Version ICO également créée à $favicon_ico<br>";
        }
        
        // Copier le logo dans le dossier Public/Logo s'il n'y est pas déjà
        if ($logo_path !== $logo_dir . '/logo_MangaHeaven.png') {
            if (copy($logo_path, $logo_dir . '/logo_MangaHeaven.png')) {
                echo "Logo copié vers Public/Logo/<br>";
            }
        }
        
        // Cleanup
        imagedestroy($source);
        imagedestroy($favicon);
    } else {
        echo "Bibliothèque GD non disponible. Veuillez créer le favicon manuellement.";
    }
} catch (Exception $e) {
    echo "Erreur lors de la création du favicon : " . $e->getMessage();
}

// Vérifier les liens dans les fichiers PHP
echo "<h2>Vérification des liens HTML</h2>";

$php_files = [
    $project_root . DIRECTORY_SEPARATOR . 'HTML_PHP' . DIRECTORY_SEPARATOR . 'Home.php',
    $project_root . DIRECTORY_SEPARATOR . 'HTML_PHP' . DIRECTORY_SEPARATOR . 'Accueil.php',
    $project_root . DIRECTORY_SEPARATOR . 'HTML_PHP' . DIRECTORY_SEPARATOR . 'Compte.php',
    $project_root . DIRECTORY_SEPARATOR . 'HTML_PHP' . DIRECTORY_SEPARATOR . 'Login.php',
    $project_root . DIRECTORY_SEPARATOR . 'HTML_PHP' . DIRECTORY_SEPARATOR . 'Register.php'
];

$correct_favicon_link = '<link rel="icon" type="image/png" href="../Public/Logo/favicon.png?v=<?php echo time(); ?>">';

foreach ($php_files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, '../Public/Logo/favicon.png') !== false) {
            echo "<p>✅ Lien correct dans " . basename($file) . "</p>";
        } else {
            echo "<p>❌ Lien incorrect dans " . basename($file) . " - sera mis à jour</p>";
            $content = preg_replace('/<link rel="icon".*>/', $correct_favicon_link, $content);
            file_put_contents($file, $content);
            echo "<p>✅ Lien mis à jour dans " . basename($file) . "</p>";
        }
    } else {
        echo "<p>❌ Fichier non trouvé: " . basename($file) . "</p>";
    }
}

echo "<h2>Vérification de la structure de dossiers</h2>";
echo "<ul>";
echo "<li>Structure actuelle choisie: Option 1 - <strong>Favicons séparés des images de contenu</strong></li>";
echo "<li>✓ Favicons: <code>Public/Logo/favicon.png</code></li>";
echo "<li>✓ Images de contenu: <code>Public/images/</code> (mangas, héros, etc.)</li>";
echo "</ul>";

echo "<p>Si votre favicon ne s'affiche toujours pas, essayez de vider le cache de votre navigateur ou de forcer l'actualisation avec Ctrl+F5.</p>";
echo "<p><a href='HTML_PHP/Home.php'>Retour à la page d'accueil</a></p>";

echo "<div style='margin-top:20px; padding:15px; background:#f8f8f8; border:1px solid #ddd;'>";
echo "<h3>Actions:</h3>";
echo "<ul>";
echo "<li><a href='../HTML_PHP/Home.php'>Aller à la page d'accueil</a></li>";
echo "<li><a href='../Script/favicon.php'>Réexécuter le script favicon.php</a></li>";
echo "<li>Pour forcer l'actualisation du favicon dans votre navigateur: <strong>Ctrl+F5</strong></li>";
echo "</ul>";
echo "</div>";
?>