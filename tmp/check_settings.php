<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
echo "backend_header_custom_css: " . getSetting('backend_header_custom_css') . "\n";
echo "admin_panel_logo: " . getSetting('admin_panel_logo') . "\n";
echo "navbar_logo: " . getSetting('navbar_logo') . "\n";
echo "enable_preloader: " . getSetting('enable_preloader') . "\n";
