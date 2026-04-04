<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Order;
use App\Models\OrderGroup;

$order = Order::where('id', 47)->first();
if (!$order) {
    echo "Order 47 not found in Order table.\n";
} else {
    echo "Order 47 found:\n";
    echo "Order Code: " . $order->order_group->order_code . "\n";
    echo "Payment Status: " . $order->payment_status . "\n";
    echo "Delivery Status: " . $order->delivery_status . "\n";
}

$orderGroup = OrderGroup::where('id', 47)->first();
if ($orderGroup) {
    echo "OrderGroup 47 found (if ID is 47):\n";
    echo "Order Code: " . $orderGroup->order_code . "\n";
}
