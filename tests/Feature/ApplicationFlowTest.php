<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ApplicationFlowTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_full_application_flow(): void
    {
        Session::start();

        $userData = [
            'name' => 'Иван Иванов',
            'email' => 'ivanivanov@test.ru',
            'password' => '12345678',
        ];

        // register
        $response = $this->postJson('/api/auth/register', $userData);
        $response->assertStatus(201);

        // login
        $response = $this->postJson('/api/auth/login', $userData);
        $response->assertStatus(200);

        // add product to cart
        $response = $this->postJson('/api/cart/add/1', ['quantity' => 4]);
        $response->assertStatus(200);

        // remove product from cart
        $response = $this->postJson('/api/cart/remove/1');
        $response->assertStatus(200);

        // get cart
        $response = $this->getJson('/api/cart');
        $response->assertStatus(200);

        // confirm order
        $response = $this->postJson('/api/order/confirm', ['address' => 'test address', 'phone_number' => 'test number', 'name' => 'test name']);
        $response->assertStatus(200);

        // logout
        $response = $this->postJson('/api/auth/logout');
        $response->assertStatus(200);
    }
}
