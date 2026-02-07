<?php
echo "<h3>Server/File Debug Info</h3>";
echo "Current File Path: " . __FILE__ . "<br>";
echo "Current Dir: " . __DIR__ . "<br>";

$path = __DIR__ . '/uploads/media';
echo "Expected Upload Path: " . $path . "<br>";

if (is_dir($path)) {
    echo "<strong>Directory Exists: YES</strong><br>";
    echo "Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "<br>";

    $files = scandir($path);
    $files = array_diff($files, array('.', '..'));

    echo "<strong>File Count: " . count($files) . "</strong><br>";
    echo "Last 5 files:<br><pre>";
    print_r(array_slice($files, -5));
    echo "</pre>";

    // Check if the specific file mentioned by user exists
    // URL was .../ju28De6HDW2JOlODLPTUFW1KWoB7BfqD103ItFOd.jpg
    // Let's check for any file starting with ju28
    echo "<strong>Checking for 'ju28...' files:</strong><br>";
    $found = false;
    foreach ($files as $f) {
        if (strpos($f, 'ju28') === 0) {
            echo "Found: " . $f . " (Size: " . filesize($path . '/' . $f) . " bytes)<br>";
            $found = true;
        }
    }
    if (!$found)
        echo "File starting with 'ju28' NOT found.<br>";

} else {
    echo "<strong>Directory Exists: NO</strong><br>";
    echo "Trying to find where 'local' disk points to...<br>";
}

echo "<br><strong>Check 'storage/app/public' just in case:</strong><br>";
$storagePath = __DIR__ . '/../storage/app/public/uploads/media';
if (is_dir($storagePath)) {
    echo "Found in storage/app/public: YES<br>";
    $files = scandir($storagePath);
    print_r(array_slice($files, -5));
} else {
    echo "Found in storage/app/public: NO<br>";
}
?>