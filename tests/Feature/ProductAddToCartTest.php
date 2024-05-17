<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ProductAddToCartTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        Session::start();

        // add product to cart
        $response = $this->postJson('/api/cart', [
            'product_id' => 1488,
        ]);
        $response->assertStatus(405);
    }
}
