<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Variations Count: " . \App\Models\Variation::count() . PHP_EOL;
echo "Variation Values Count: " . \App\Models\VariationValue::count() . PHP_EOL;
