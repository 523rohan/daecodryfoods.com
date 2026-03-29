<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Modules\Support\Entities\TicketFile;

$files = TicketFile::all();
foreach ($files as $file) {
    echo "ID: {$file->id} | Path: {$file->file_path}\n";
}
