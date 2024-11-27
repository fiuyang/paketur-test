<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ManagerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetManager()
    {
        $token = $this->createUserWithRole('manager');

        $company = Company::factory()->create();
        $role = Role::factory()->create(['name' => 'manager']);
        User::factory()->create(['role_id' => $role->id, 'company_id' => $company->id]);
        User::factory()->count(5)->create(['role_id' => $role->id, 'company_id' => $company->id]);
        $response = $this->getJson('/api/managers', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'role_id',
                        'company_id',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'role' => [
                            'id',
                            'name',
                            'created_at',
                            'updated_at',
                        ],
                        'company' => [
                            'id',
                            'name',
                            'email',
                            'deleted_at',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ]);
    }

    public function testGetManagerWithPagingAndSearch(): void
    {
        $token = $this->createUserWithRole('manager');

        $company = Company::factory()->create();
        $role = Role::factory()->create(['name' => 'manager']);
        User::factory()->create(['role_id' => $role->id, 'company_id' => $company->id]);
        User::factory()->count(25)->create(['role_id' => $role->id, 'company_id' => $company->id]);
        $response = $this->getJson('/api/managers?search=john&sort=asc&limit=1&page=1', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data','links']);
    }

    public function testGetManagerById(): void
    {
        $token = $this->createUserWithRole('manager');

        $company = Company::factory()->create();
        $role = Role::factory()->create(['name' => 'manager']);
        User::factory()->create(['role_id' => $role->id, 'company_id' => $company->id]);
        $anotherManager = User::factory()->create(['role_id' => $role->id, 'company_id' => $company->id]);
        $response = $this->getJson("/api/managers/{$anotherManager->id}",  [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'role_id',
                    'company_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'role' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                    'company' => [
                        'id',
                        'name',
                        'email',
                        'phone_number',
                        'deleted_at',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    public function testGetManagerByIdNotFound()
    {
        $token = $this->createUserWithRole('manager');

        $company = Company::factory()->create();
        $role = Role::factory()->create(['name' => 'manager']);
        User::factory()->create(['role_id' => $role->id, 'company_id' => $company->id]);
        $response = $this->getJson("/api/managers/100",  [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'record not found']);
    }

    public function testGetManagerByIdWhenCannotAccess()
    {
        $token = $this->createUserWithRole('employee');

        $company = Company::factory()->create();
        $managerRole = Role::factory()->create(['name' => 'manager']);
        $employeeRole = Role::factory()->create(['name' => 'employee']);
        User::factory()->count(5)->create(['role_id' => $managerRole->id, 'company_id' => $company->id]);
        User::factory()->create(['role_id' => $employeeRole->id, 'company_id' => $company->id]);
        $response = $this->getJson("/api/managers",  [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'forbidden access']);
    }
}
