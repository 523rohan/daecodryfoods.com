<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

echo 'Total Orders: ' . Order::count() . "\n";
echo 'Online Orders: ' . Order::whereHas('orderGroup', function($q){$q->where('is_pos_order', 0);})->count() . "\n";
echo 'POS Orders: ' . Order::whereHas('orderGroup', function($q){$q->where('is_pos_order', 1);})->count() . "\n";
echo 'Paid Orders: ' . Order::isPaid()->count() . "\n";
echo 'Paid Pending Orders: ' . Order::isPlacedOrPending()->isPaid()->count() . "\n";
