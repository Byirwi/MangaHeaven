<?php
// This file will convert your logo to a favicon and save it

// Check if logo exists
$logo_path = __DIR__ . '/images/logo_MangaHeaven.png';
$favicon_png = __DIR__ . '/favicon.png';
$favicon_ico = __DIR__ . '/favicon.ico';

if (!file_exists($logo_path)) {
    die("Logo file not found at: $logo_path");
}

// Create the favicon.png (resized version of logo)
try {
    if (extension_loaded('gd')) {
        // Load the original image
        $source = imagecreatefrompng($logo_path);
        if (!$source) {
            die("Could not create image from PNG. Check if the file is a valid PNG image.");
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
        
        echo "Favicon created successfully at $favicon_png<br>";
        if (file_exists($favicon_ico)) {
            echo "ICO version also created at $favicon_ico<br>";
        }
        
        // Cleanup
        imagedestroy($source);
        imagedestroy($favicon);
    } else {
        echo "GD library not available. Please create favicon manually.";
    }
} catch (Exception $e) {
    echo "Error creating favicon: " . $e->getMessage();
}

echo "<p>If your favicon still doesn't show up, try clearing your browser cache or forcing a refresh with Ctrl+F5.</p>";
?>
