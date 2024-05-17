<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class InvalidLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
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

        $userData = [
            'email' => 'ivanivanov@test.ru',
            'password' => 'badPassword',
        ];

        // login
        $response = $this->postJson('/api/auth/login', $userData);
        $response->assertStatus(401);
    }
}
