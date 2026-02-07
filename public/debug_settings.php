<?php
// Load Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Get all settings
$settings = \App\Models\SystemSetting::all();

echo "<h3>System Settings (Filtered)</h3>";
echo "<table border='1'><tr><th>Key</th><th>Value</th></tr>";

$keywords = ['popup', 'banner', 'image', 'promo', 'modal', 'offer'];

foreach ($settings as $s) {
    $match = false;
    foreach ($keywords as $k) {
        if (stripos($s->entity, $k) !== false) {
            $match = true;
            break;
        }
    }

    if ($match) {
        echo "<tr>";
        echo "<td>" . $s->entity . "</td>";
        echo "<td>" . htmlspecialchars(substr($s->value, 0, 100)) . "</td>";
        echo "</tr>";
    }
}
echo "</table>";
?>