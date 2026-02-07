<?php
echo "<h3>Server/File Debug Info (Update)</h3>";
echo "Current File Path: " . __FILE__ . "<br>";
echo "Current Dir: " . __DIR__ . "<br>";

$path = __DIR__ . '/uploads/media';
echo "Target Upload Path: " . $path . "<br>";

if (!is_dir($path)) {
    echo "Directory does not exist. Creating...<br>";
    if (mkdir($path, 0755, true)) {
        echo "Created directory successfully.<br>";
    } else {
        echo "Failed to create directory.<br>";
        exit;
    }
}

// 1. Check Permissions
echo "Current Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "<br>";

// 2. Try to Fix Permissions (chmod 0755)
echo "Attempting to change permissions to 0755...<br>";
if (chmod($path, 0755)) {
    echo "chmod Success!<br>";
} else {
    echo "chmod Failed (Current user might not be owner).<br>";
}
echo "New Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "<br>";

// 3. Try to Write a Test File
$testFile = $path . '/test_write_' . time() . '.txt';
echo "Attempting to write test file: $testFile<br>";
if (file_put_contents($testFile, "This is a test write from PHP script.")) {
    echo "<strong>Write Test: SUCCESS!</strong> File created.<br>";
    echo "File Size: " . filesize($testFile) . " bytes<br>";
} else {
    echo "<strong>Write Test: FAILED!</strong> Could not write file.<br>";
    $lastError = error_get_last();
    echo "Error: " . $lastError['message'] . "<br>";
}

// 4. List Files Again
$files = scandir($path);
$files = array_diff($files, array('.', '..'));
echo "<br><strong>File Count: " . count($files) . "</strong><br>";
echo "Files found:<br><pre>";
print_r($files);
echo "</pre>";
?>