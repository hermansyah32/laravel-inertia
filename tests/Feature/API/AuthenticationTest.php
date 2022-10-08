<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate()
    {
        $user = User::factory()->create();
        $response = $this->post('/api/login', [
            'login' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();
        $response = $this->post('/api/login', [
            'login' => $user->email,
            'password' => 'wrong-password',
        ]);
        $response->assertStatus(401);
    }
}
