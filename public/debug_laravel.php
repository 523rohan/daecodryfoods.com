<?php
// Load Laravel Infrastructure
// Assumes standard Laravel structure relative to public/
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Create Console Kernel to boot application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h3>Laravel Configuration</h3>";

echo "<strong>public_path():</strong> " . public_path() . "<br>";
echo "<strong>base_path():</strong> " . base_path() . "<br>";
echo "<strong>storage_path():</strong> " . storage_path() . "<br>";

echo "<br><strong>Filesystem Config:</strong><br>";
$defaultDisk = config('filesystems.default');
echo "Default Disk: " . $defaultDisk . "<br>";
echo "Disks[" . $defaultDisk . "][root]: " . config("filesystems.disks.{$defaultDisk}.root") . "<br>";

echo "<br><strong>Test Write via Storage Facade:</strong><br>";
try {
    $path = 'uploads/media/test_laravel_write_' . time() . '.txt';
    $success = \Illuminate\Support\Facades\Storage::disk($defaultDisk)->put($path, "This file was written via Laravel Storage facade.");

    if ($success) {
        echo "Storage::put('$path') returned <strong>TRUE</strong>.<br>";

        $fullPath = config("filesystems.disks.{$defaultDisk}.root") . '/' . $path;
        echo "Looking for file at: " . $fullPath . "<br>";

        if (file_exists($fullPath)) {
            echo "File <strong>EXISTS</strong> on disk.<br>";
        } else {
            echo "File <strong>DOES NOT EXIST</strong> on disk (Check permissions or path mappings).<br>";
        }

    } else {
        echo "Storage::put('$path') returned <strong>FALSE</strong>.<br>";
    }
} catch (\Exception $e) {
    echo "Exception during write: " . $e->getMessage() . "<br>";
}
?>