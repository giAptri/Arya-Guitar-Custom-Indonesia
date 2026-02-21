<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_code' => $this->order_code,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'customer_name' => $this->customer_name ?? $this->customer?->name, // Snapshot with fallback
            'customer_phone' => $this->customer_phone ?? $this->customer?->phone, // Snapshot with fallback
            'reference_photo' => $this->reference_photo_path ? asset('storage/' . $this->reference_photo_path) : null,
            'guitar' => new GuitarResource($this->whenLoaded('guitar')),
            'guitar_type' => $this->guitar_type,
            'pickup_config' => $this->pickup_config,
            'orientation' => $this->orientation,
            'notes' => $this->notes,
            'estimated_price' => $this->estimated_price,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
