<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Category;
use App\Models\Theme;

echo "<h1>Category Debugger</h1>";

$params = [0, null];
$categories = Category::withoutGlobalScopes()->where(function ($q) {
    $q->where('parent_id', 0)->orWhereNull('parent_id');
})->get();

echo "<table border='1' cellpadding='5'><thead><tr><th>ID</th><th>Name</th><th>Parent ID</th><th>Themes (Count)</th><th>Themes (Names)</th><th>Deleted At</th></tr></thead><tbody>";

foreach ($categories as $cat) {
    echo "<tr>";
    echo "<td>{$cat->id}</td>";
    echo "<td>{$cat->collectLocalization('name')}</td>";
    echo "<td>" . var_export($cat->parent_id, true) . "</td>";

    $themes = $cat->themes;
    echo "<td>" . $themes->count() . "</td>";
    echo "<td>" . $themes->pluck('name')->implode(', ') . "</td>";
    echo "<td>" . $cat->deleted_at . "</td>";
    echo "</tr>";
}
echo "</tbody></table>";

echo "<h2>Current Theme Helper Check</h2>";
try {
    echo "Current Theme Key: " . getTheme() . "<br>";
    $theme = Theme::where('code', getTheme())->first();
    echo "Current Theme ID: " . ($theme ? $theme->id : 'Not Found') . "<br>";
} catch (\Exception $e) {
    echo "Error checking theme: " . $e->getMessage();
}

