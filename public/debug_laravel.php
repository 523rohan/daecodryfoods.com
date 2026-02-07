<?php
// Load Laravel Infrastructure
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Create Console Kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h3>Laravel Image Upload Simulation</h3>";

echo "<strong>public_path():</strong> " . public_path() . "<br>";
echo "<strong>Default Disk:</strong> " . config('filesystems.default') . "<br>";
echo "<strong>Disk Check:</strong> " . config('filesystems.disks.local.root') . "<br>";

echo "<br><strong>Simulating Upload:</strong><br>";

try {
    // Create a fake uploaded file
    $file = \Illuminate\Http\UploadedFile::fake()->image('test_image.jpg');

    // Attempt store() using default disk
    $path = $file->store('uploads/media');

    echo "Result Path from store(): " . $path . "<br>";

    // Check if file exists at that path on disk
    if (\Illuminate\Support\Facades\Storage::exists($path)) {
        echo "Storage::exists('$path') returned <strong>TRUE</strong>.<br>";

        $fullPath = \Illuminate\Support\Facades\Storage::path($path);
        echo "Full Path: " . $fullPath . "<br>";

        if (file_exists($fullPath)) {
            echo "Direct file_exists() check: <strong>FOUND</strong>.<br>";
            echo "File Size: " . filesize($fullPath) . " bytes<br>";
        } else {
            echo "Direct file_exists() check: <strong>NOT FOUND</strong>.<br>";
        }

    } else {
        echo "Storage::exists('$path') returned <strong>FALSE</strong>.<br>";
    }

    // Also check public path directly
    $publicFullPath = public_path($path);
    echo "Check public_path('$path'): " . $publicFullPath . "<br>";
    if (file_exists($publicFullPath)) {
        echo "Found via public_path(): <strong>YES</strong>.<br>";
    } else {
        echo "Found via public_path(): <strong>NO</strong>.<br>";
    }

} catch (\Exception $e) {
    echo "Exception during upload simulation: " . $e->getMessage() . "<br>";
    echo "Trace: <pre>" . $e->getTraceAsString() . "</pre>";
}
?>