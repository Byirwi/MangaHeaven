<?php
// This file will convert your logo to a favicon and save it

// Créer les dossiers s'ils n'existent pas
$public_dir = __DIR__ . '/Public';
$logo_dir = $public_dir . '/Logo';

if (!file_exists($public_dir)) {
    mkdir($public_dir, 0755);
    echo "Dossier Public créé.<br>";
}

if (!file_exists($logo_dir)) {
    mkdir($logo_dir, 0755);
    echo "Dossier Public/Logo créé.<br>";
}

// Définir tous les emplacements possibles du logo
$possible_logo_paths = [
    __DIR__ . '/Public/Logo/logo_MangaHeaven.png',
];

// Rechercher le logo dans tous les emplacements possibles
$logo_path = null;
foreach ($possible_logo_paths as $path) {
    if (file_exists($path)) {
        $logo_path = $path;
        echo "Logo trouvé à: $path<br>";
        break;
    }
}

// Définir les chemins de destination
$favicon_png = __DIR__ . '/Public/Logo/favicon.png';
$favicon_ico = __DIR__ . '/Public/Logo/favicon.ico';

// Si le logo n'est trouvé dans aucun emplacement
if ($logo_path === null) {
    echo "<h2>Logo introuvable</h2>";
    echo "<p>Le logo n'a pas été trouvé. J'ai cherché aux emplacements suivants:</p>";
    echo "<ul>";
    foreach ($possible_logo_paths as $path) {
        echo "<li>" . htmlspecialchars($path) . "</li>";
    }
    echo "</ul>";
    echo "<p>Veuillez placer le fichier <code>logo_MangaHeaven.png</code> dans l'un des dossiers ci-dessus.</p>";
    echo "<p>Vous pouvez également téléverser votre logo maintenant:</p>";
    
    // Formulaire simple pour téléverser un logo
    echo "<form action='' method='post' enctype='multipart/form-data'>";
    echo "<input type='file' name='logo_file' accept='image/png'>";
    echo "<input type='submit' name='submit' value='Téléverser le logo'>";
    echo "</form>";
    
    // Traitement du téléversement du logo
    if (isset($_POST['submit'])) {
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['logo_file']['tmp_name'];
            $name = $_FILES['logo_file']['name'];
            
            // Vérifier que c'est bien un fichier PNG
            $image_info = getimagesize($tmp_name);
            if ($image_info[2] === IMAGETYPE_PNG) {
                // Créer le dossier de destination si nécessaire
                if (!file_exists($logo_dir)) {
                    mkdir($logo_dir, 0755, true);
                }
                
                // Déplacer le fichier vers le dossier Public/Logo
                $logo_path = $logo_dir . '/logo_MangaHeaven.png';
                if (move_uploaded_file($tmp_name, $logo_path)) {
                    echo "<p>Logo téléversé avec succès!</p>";
                    // Continuer le script (pas de die())
                } else {
                    die("Erreur lors du déplacement du fichier téléversé.");
                }
            } else {
                die("Le fichier doit être au format PNG.");
            }
        } else {
            die("Erreur lors du téléversement: " . $_FILES['logo_file']['error']);
        }
    } else {
        die();
    }
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