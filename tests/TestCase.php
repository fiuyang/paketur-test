<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Create a user with a given role and generate JWT token.
     *
     * @param  string  $role
     * @return array
     */
    public function createUserWithRole(string $role): string
    {
        Role::insert([
            ['name' => 'super_admin'],
            ['name' => 'manager'],
            ['name' => 'employee']
        ]);
        $role = Role::where('name', $role)->first();

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
        // Generate JWT token for the user
        $token = JWTAuth::fromUser($user);

        return $token;
    }

}
