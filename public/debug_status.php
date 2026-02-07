<?php
echo "<h3>Server Status Check</h3>";

// 1. PHP Limits
echo "<strong>PHP Upload Limits:</strong><br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_execution_time: " . ini_get('max_execution_time') . "<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";

// 2. Check for .htaccess inside public/
$htaccess = __DIR__ . '/.htaccess';
echo "<br><strong>.htaccess in public/:</strong> ";
if (file_exists($htaccess)) {
    echo "EXISTS.<br><pre>" . htmlspecialchars(file_get_contents($htaccess)) . "</pre>";
} else {
    echo "DOES NOT EXIST.<br>";
}

// 3. List uploads/media files (Check if the failed file is here)
$path = __DIR__ . '/uploads/media';
echo "<br><strong>Files in uploads/media (Last 10):</strong><br>";
if (is_dir($path)) {
    $files = scandir($path);
    $files = array_diff($files, array('.', '..'));
    // Sort by time descending
    usort($files, function ($a, $b) use ($path) {
        return filemtime($path . '/' . $b) - filemtime($path . '/' . $a);
    });

    echo "Folder Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "<br>";
    echo "Folder Owner: " . fileowner($path) . "<br>";
    echo "Current Script Owner: " . get_current_user() . " (" . getmyuid() . ")<br>";

    $recent = array_slice($files, 0, 10); // Show most recent 10
    echo "<pre>";
    foreach ($recent as $f) {
        echo $f . " (Size: " . filesize($path . '/' . $f) . " bytes, Modified: " . date("F d Y H:i:s", filemtime($path . '/' . $f)) . ")\n";
    }
    echo "</pre>";
} else {
    echo "Directory not found.<br>";
}
?>