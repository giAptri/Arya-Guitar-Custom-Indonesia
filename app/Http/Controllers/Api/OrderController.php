<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status'); // pending|produksi|selesai
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);
        $user = $request->user();

        $q = Order::query()
            ->with(['customer', 'guitar'])
            ->latest('created_at');

        // RBAC Filter
        if ($user->role !== 'admin') {
             if (!$user->customer) {
                 return OrderResource::collection([]);
             }
             $q->where('customer_id', $user->customer->id);
        }

        if ($status) $q->where('status', $status);

        if ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('order_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($c) use ($search) {
                        $c->where('name', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        return OrderResource::collection($q->paginate($perPage));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],

            'guitar_id' => ['nullable', 'integer', 'exists:guitars,id'],

            'guitar_type' => ['required', Rule::in(['electric', 'acoustic', 'bass'])],
            'pickup_config' => ['nullable', Rule::in(['SSS', 'HSS', 'HH'])],
            'orientation' => ['required', Rule::in(['right', 'left'])],

            'notes' => ['nullable', 'string'],
            'estimated_price' => ['nullable', 'numeric', 'min:0'],
            'reference_photo' => ['nullable', 'image', 'max:2048'], // 2MB Max
        ]);

        $user = $request->user(); // Authenticated User
        $phone = trim($data['customer_phone']);

        // Check if user already has a customer profile associated
        $customer = $user->customer;

        if ($customer) {
            // Update phone if different
             if ($customer->phone !== $phone) {
                 $customer->update(['phone' => $phone]);
             }
             // Update name if needed (optional logic from before)
             if ($customer->name !== $data['customer_name'] && strlen($data['customer_name']) > strlen($customer->name)) {
                 $customer->update(['name' => $data['customer_name']]);
             }
        } else {
            // Create new customer profile linked to this user
             $customer = Customer::create([
                 'user_id' => $user->id,
                 'name' => $data['customer_name'],
                 'phone' => $phone,
             ]);
        }

        // Handle Photo Upload
        $photoPath = null;
        if ($request->hasFile('reference_photo')) {
            $photoPath = $request->file('reference_photo')->store('orders/photos', 'public');
        }

        $order = Order::create([
            // 'order_code' handled by Model boot
            'customer_id' => $customer->id,
            'customer_name' => $data['customer_name'], // Snapshot
            'customer_phone' => $phone, // Snapshot
            'guitar_id' => $data['guitar_id'] ?? null,
            'guitar_type' => $data['guitar_type'],
            'pickup_config' => $data['pickup_config'] ?? null,
            'orientation' => $data['orientation'],
            'notes' => $data['notes'] ?? null,
            'estimated_price' => $data['estimated_price'] ?? null,
            'reference_photo_path' => $photoPath,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('new_order', $order->load(['customer', 'guitar']));
    }

    public function show(Request $request, Order $order)
    {
        $user = $request->user();

        // Admin can view any order
        if ($user->role === 'admin') {
            return new OrderResource($order->load(['customer', 'guitar']));
        }

        // Customer can only view their own order
        if (!$user->customer || $order->customer_id !== $user->customer->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        return new OrderResource($order->load(['customer', 'guitar']));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'produksi', 'selesai'])],
        ]);

        $order->update(['status' => $data['status']]);

        return response()->json([
            'message' => 'Status pesanan berhasil diperbarui.',
            'data' => new OrderResource($order->fresh()->load(['customer', 'guitar'])),
        ]);
    }
}
