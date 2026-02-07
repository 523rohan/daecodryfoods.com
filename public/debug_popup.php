<?php
// Load Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<h3>Targeted Settings Search</h3>";
echo "<table border='1'><tr><th>Key</th><th>Value</th></tr>";

$keywords = ['popup', 'modal', 'promo', 'offer', 'subscribe', 'newsletter', 'welcome', 'organic_'];

foreach ($keywords as $word) {
    $settings = \App\Models\SystemSetting::where('entity', 'LIKE', '%' . $word . '%')->get();
    foreach ($settings as $s) {
        echo "<tr>";
        echo "<td>" . $s->entity . "</td>";
        echo "<td>" . htmlspecialchars(substr($s->value, 0, 100)) . "</td>";
        echo "</tr>";
    }
}
echo "</table>";
?>