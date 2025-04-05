<?php
// This file will convert your logo to a favicon and save it

// Créer les dossiers s'ils n'existent pas
$public_dir = __DIR__ . '/Public';
$images_dir = $public_dir . '/images';

if (!file_exists($public_dir)) {
    mkdir($public_dir, 0755);
    echo "Dossier Public créé.<br>";
}

if (!file_exists($images_dir)) {
    mkdir($images_dir, 0755);
    echo "Dossier Public/images créé.<br>";
}

// Définir les chemins
$logo_path = __DIR__ . '/Public/images/logo_MangaHeaven.png';
$favicon_png = __DIR__ . '/Public/images/favicon.png';
$favicon_ico = __DIR__ . '/Public/images/favicon.ico';

// Si le logo n'existe pas dans le nouveau chemin mais existe dans l'ancien
$old_logo_path = __DIR__ . '/images/logo_MangaHeaven.png';
if (!file_exists($logo_path) && file_exists($old_logo_path)) {
    // Copier le logo dans le nouveau dossier
    copy($old_logo_path, $logo_path);
    echo "Logo copié de l'ancien emplacement vers le nouveau.<br>";
}

if (!file_exists($logo_path)) {
    die("Logo introuvable. Veuillez placer le fichier logo_MangaHeaven.png dans le dossier Public/images.");
}

// Create the favicon.png (resized version of logo)
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
        
        // Cleanup
        imagedestroy($source);
        imagedestroy($favicon);
    } else {
        echo "Bibliothèque GD non disponible. Veuillez créer le favicon manuellement.";
    }
} catch (Exception $e) {
    echo "Erreur lors de la création du favicon : " . $e->getMessage();
}

echo "<p>Si votre favicon ne s'affiche toujours pas, essayez de vider le cache de votre navigateur ou de forcer l'actualisation avec Ctrl+F5.</p>";
echo "<p><a href='HTML_PHP/Home.php'>Retour à la page d'accueil</a></p>";
?>
