<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginSuccess()
    {
        $role = Role::factory()->create(['name' => 'super_admin']);
        $user = User::factory()->create([
            'role_id' => $role->id,
            'password' => 'password'
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);

        $response->assertJson([
            'token_type' => 'bearer',
            'expires_in' => 3600
        ]);
    }

    public function testLoginFailsWithEmailWrong()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'notfound@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response->assertJson([
            'message' => 'email or password is wrong',
        ]);
    }

    public function testLoginFailsWithPasswordWrong()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'notfound@example.com',
            'password' => '12455678',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response->assertJson([
            'message' => 'email or password is wrong',
        ]);
    }
}
