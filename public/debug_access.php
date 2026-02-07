<?php
echo "<h3>Subfolder Access Check</h3>";

$dirs = array(
    'uploads',
    'uploads/media'
);

foreach ($dirs as $d) {
    $fullPath = __DIR__ . '/' . $d;
    echo "<strong>Checking: " . $d . "</strong><br>";
    if (is_dir($fullPath)) {
        $htaccess = $fullPath . '/.htaccess';
        if (file_exists($htaccess)) {
            echo "<strong>FOUND .htaccess in $d:</strong><br><pre>" . htmlspecialchars(file_get_contents($htaccess)) . "</pre>";
        } else {
            echo "No .htaccess found in $d.<br>";
        }
    } else {
        echo "Directory $d not found.<br>";
    }
    echo "<br>";
}

// Also, list some test files with links to try
$mediaPath = __DIR__ . '/uploads/media';
if (is_dir($mediaPath)) {
    echo "<strong>Files to try accessing (Click these):</strong><br>";
    $files = scandir($mediaPath);
    $files = array_diff($files, array('.', '..'));
    $files = array_slice($files, -10); // Last 10

    foreach ($files as $f) {
        $link = 'uploads/media/' . $f;
        echo "<a href='$link' target='_blank'>$f</a><br>";
    }
}
?>