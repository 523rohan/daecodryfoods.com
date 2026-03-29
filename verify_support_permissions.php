<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;

$permissions = ['support.index', 'support.category.index', 'support.priority.index', 'support.ticket.index'];
$missing = [];

foreach ($permissions as $p) {
    if (!Permission::where('name', $p)->exists()) {
        $missing[] = $p;
    }
}

if (empty($missing)) {
    echo "SUCCESS: All Support Permissions exist.";
} else {
    echo "MISSING: " . implode(', ', $missing);
}
