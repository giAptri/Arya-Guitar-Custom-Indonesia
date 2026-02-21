<?php
echo "Guitars: " . \App\Models\Guitar::where('is_active', true)->count() . "\n";
echo "Orders This Month: " . \App\Models\Order::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count() . "\n";
echo "Customers: " . \App\Models\Customer::has('orders')->count() . "\n";
