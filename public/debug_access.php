<?php
// Handle Proxy Request if 'proxy' param is set
if (isset($_GET['proxy'])) {
    $file = basename($_GET['proxy']);
    $path = __DIR__ . '/uploads/media/' . $file;
    if (file_exists($path)) {
        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        readfile($path);
        exit;
    } else {
        die("File not found via PHP proxy: " . $file);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Access Check</title>
</head>

<body>
    <h3>File Access Check (Direct vs PHP Proxy)</h3>

    <?php
    $mediaPath = __DIR__ . '/uploads/media';
    if (is_dir($mediaPath)) {
        $files = scandir($mediaPath);
        $files = array_diff($files, array('.', '..'));

        // Sort by recent
        usort($files, function ($a, $b) use ($mediaPath) {
            return filemtime($mediaPath . '/' . $b) - filemtime($mediaPath . '/' . $a);
        });

        $recent = array_slice($files, 0, 10);

        echo "<table border='1' cellpadding='5'><tr><th>File</th><th>Size</th><th>Permissions</th><th>Link 1 (Direct)</th><th>Link 2 (Via PHP)</th></tr>";

        foreach ($recent as $f) {
            $path = $mediaPath . '/' . $f;
            $size = filesize($path);
            $perms = substr(sprintf('%o', fileperms($path)), -4);

            $directLink = 'uploads/media/' . $f;
            $proxyLink = '?proxy=' . urlencode($f);

            echo "<tr>";
            echo "<td>$f</td>";
            echo "<td>$size bytes</td>";
            echo "<td>$perms</td>";
            echo "<td><a href='$directLink' target='_blank'>Check Direct</a></td>";
            echo "<td><a href='$proxyLink' target='_blank'>Check via PHP</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Folder not found: $mediaPath";
    }
    ?>
</body>

</html>