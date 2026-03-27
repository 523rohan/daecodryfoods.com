<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

echo 'Total Orders: ' . Order::count() . "\n";
echo 'Status order_placed (Paid): ' . Order::isPlaced()->isPaid()->count() . "\n";
echo 'Status pending (Paid): ' . Order::isPending()->isPaid()->count() . "\n";
echo 'Status order_placed (Unpaid): ' . Order::isPlaced()->isUnpaid()->count() . "\n";
echo 'Status pending (Unpaid): ' . Order::isPending()->isUnpaid()->count() . "\n";
