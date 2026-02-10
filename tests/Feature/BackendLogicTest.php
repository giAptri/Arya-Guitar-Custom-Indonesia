<?php

namespace Tests\Feature;

use App\Models\Guitar;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BackendLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_guitar_slug_is_generated_automatically()
    {
        $guitar = Guitar::create([
            'name' => 'Fender Stratocaster Custom',
            'base_price' => 15000000,
            'is_active' => true,
        ]);

        $this->assertEquals('fender-stratocaster-custom', $guitar->slug);
    }

    public function test_guitar_slug_is_unique()
    {
        Guitar::create(['name' => 'Fender Stratocaster', 'is_active' => true]);
        $guitar2 = Guitar::create(['name' => 'Fender Stratocaster', 'is_active' => true]);

        $this->assertNotEquals('fender-stratocaster', $guitar2->slug);
        $this->assertTrue(in_array($guitar2->slug, ['fender-stratocaster-2', 'fender-stratocaster-1']));
    }

    public function test_order_code_is_generated_automatically()
    {
        $order = Order::create([
            'customer_id' => 1, // Assumptions: foreign key checks might fail if not handled, but RefreshDatabase should handle migrations if they exist
            'guitar_type' => 'electric',
            'orientation' => 'right',
            'status' => 'pending'
        ]);

        $this->assertStringStartsWith('ORD-', $order->order_code);
        $this->assertEquals(15, strlen($order->order_code)); // ORD-YYYY-XXXXXX (4+4+1+6 = 15)
    }
}
