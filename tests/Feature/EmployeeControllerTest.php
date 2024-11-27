<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetEmployeeByIdNotFound()
    {
        $token = $this->createUserWithRole('manager');

        $company = Company::factory()->create();
        $managerRole = Role::factory()->create(['name' => 'manager']);
        $employeeRole = Role::factory()->create(['name' => 'employee']);
        User::factory()->create([
            'role_id' => $managerRole->id,
            'company_id' => $company->id
        ]);
        User::factory()->create([
            'role_id' => $employeeRole->id,
            'company_id' => $company->id
        ]);
        $response = $this->getJson("/api/employees/100", [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'record not found']);
    }

    public function testUnauthorizedCannotCreateEmployee()
    {
        $token = $this->createUserWithRole('super_admin');

        $payload = [
            'name' => 'John',
            'phone_number' => '08764646464',
            'address' => 'Jln Ngantru'
        ];
        $response = $this->postJson('/api/employees', $payload, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'forbidden access']);
    }

    public function testUnauthorizedCannotUpdateEmployee()
    {
        $token = $this->createUserWithRole('super_admin');

        $employee = Employee::factory()->create();
        $payload = [
            'name' => 'John',
            'phone_number' => '08764646464',
            'address' => 'Jln Ngantru'
        ];
        $response = $this->putJson("/api/employees/{$employee->id}", $payload, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'forbidden access']);
    }

    public function testUnauthorizedCannotDeleteEmployee()
    {
        $token = $this->createUserWithRole('super_admin');

        $employee = Employee::factory()->create();
        $response = $this->deleteJson("/api/employees/{$employee->id}", [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'forbidden access']);
    }

    public function testGetEmployeeAsEmployee()
    {
        $token = $this->createUserWithRole('employee');

        Employee::factory()->count(2)->create();

        $response = $this->getJson("/api/employees", [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'phone_number',
                        'address',
                        'user_id',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ]
                ]
            ]);
    }

    public function testGetEmployeeWithPagingAndSearchAsManager()
    {
        $token = $this->createUserWithRole('manager');

        Employee::factory()->count(2)->create();

        $response = $this->getJson("/api/employees?search=john&sort=asc&limit=5&page=1", [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data']);
    }

    public function testCreateEmployeeValidationError()
    {
        $token = $this->createUserWithRole('manager');

        $response = $this->postJson('/api/employees', [
            'name' => 'John',
            'phone_number' => '08764646464',
            'address' => ''
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure([
                'errors' => []
            ]);
    }

    public function testCreateEmployeeAsManager()
    {
        $token = $this->createUserWithRole('manager');

        $payload = [
            'name' => 'John',
            'phone_number' => '08764646464',
            'address' => 'Jln Ngantru'
        ];
        $response = $this->postJson('/api/employees', $payload, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'employee successfully created']);
    }

    public function testGetEmployeeByIdAsManager()
    {
        $token = $this->createUserWithRole('manager');

        $employee = Employee::factory()->create();
        $response = $this->getJson("/api/employees/{$employee->id}", [
            'Authorization' => 'Bearer ' . $token
        ]);
        Log::info($response->json());
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'phone_number',
                    'address',
                    'user_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'role_id',
                        'company_id',
                        'deleted_at',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    public function testUpdateEmployeeValidationError()
    {
        $token = $this->createUserWithRole('manager');

        $employee = Employee::factory()->create();

        $payload = [
            'name' => 'John',
            'phone_number' => '',
            'address' => 'Jln Ngantru'
        ];
        $response = $this->putJson("/api/employees/{$employee->id}", $payload, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure([
                'errors' => []
            ]);
    }

    public function testUpdateEmployeeAsManager()
    {
        $token = $this->createUserWithRole('manager');
        $employee = Employee::factory()->create();

        $payload = [
            'name' => 'John',
            'phone_number' => '08764646464',
            'address' => 'Jln Ngantru'
        ];
        $response = $this->putJson("/api/employees/{$employee->id}", $payload, [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'employee successfully updated']);
    }

    public function testManagerDeleteEmployee()
    {
        $token = $this->createUserWithRole('manager');
        $employee = Employee::factory()->create();

        $response = $this->deleteJson("/api/employees/{$employee->id}", [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'employee successfully deleted']);
    }
}
