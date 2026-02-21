<?php

use App\Models\Order;

// Fix estimated_price for orders where it is null
$orders = Order::whereNull('estimated_price')->get();

foreach ($orders as $order) {
    // Generate random price between 1.000.000 and 15.000.000
    // Round to nearest 500.000 for cleaner look
    $price = rand(2, 30) * 500000;

    $order->update(['estimated_price' => $price]);
    echo "Updated Order #{$order->id} ({$order->order_code}) with price: Rp " . number_format($price, 0, ',', '.') . "\n";
}

echo "Done! Updated " . $orders->count() . " orders.\n";
