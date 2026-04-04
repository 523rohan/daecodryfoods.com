<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Order;
use App\Models\OrderGroup;
use App\Http\Controllers\Frontend\CheckoutController;

$order_id = 47;
$order = Order::find($order_id);

if (!$order) {
    die("Order $order_id not found.\n");
}

$orderGroup = $order->order_group;
if (!$orderGroup) {
    die("OrderGroup for Order $order_id not found.\n");
}

echo "Found Order: " . $order->id . " with Order Code: " . $orderGroup->order_code . "\n";
echo "Current Payment Status: " . $orderGroup->payment_status . "\n";

$payment_details = json_encode([
    'order_code' => $orderGroup->order_code,
    'transaction_id' => 'OM2604022021216762735033V',
    'amount' => 170.00,
    'note' => 'Manual fix based on PhonePe screenshot provided by user',
    'method' => 'phonepe'
]);

// Set necessary session data if needed by updatePayments, 
// but my new updatePayments is more robust.
session(['order_code' => $orderGroup->order_code]);
session(['payment_method' => 'phonepe']);
session(['payment_type' => 'order_payment']);

echo "Updating order status...\n";
$controller = new CheckoutController();
$controller->updatePayments($payment_details, $orderGroup->order_code);

echo "Order $order_id has been successfully marked as PAID and CONFIRMED.\n";
