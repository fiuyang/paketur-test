<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCannotAccessCreateCompany()
    {
        $token = $this->createUserWithRole('manager');

        $response = $this->postJson('/api/companies', [
            'name' => 'BCA',
            'email' => 'bca@gmail.com',
            'phone_number' => '08123456789',
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'forbidden access']);
    }

    public function testCannotAccessDeleteCompany()
    {
        $token = $this->createUserWithRole('manager');
        $company = Company::factory()->create();
        $response = $this->delete("/api/companies/{$company->id}", [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'forbidden access']);
    }

    public function testCreateCompanyValidationFails()
    {
        $token = $this->createUserWithRole('super_admin');

        $response = $this->postJson('/api/companies', [
            'name' => '',
            'email' => 'bri@gmail.com',
            'phone_number' => '08123456789',
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure([
                'errors' => []
            ]);
    }

    public function testCreateCompanyAsSuperAdmin()
    {
        $token = $this->createUserWithRole('super_admin');

        $response = $this->postJson('/api/companies', [
            'name' => 'BRI',
            'email' => 'bri@gmail.com',
            'phone_number' => '08123456789',
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'company successfully created']);

        $this->assertDatabaseHas('companies', [
            'name' => 'BRI',
            'email' => 'bri@gmail.com',
            'phone_number' => '08123456789',
        ]);
    }

    public function testDeleteCompanyAsSuperAdmin()
    {
        $token = $this->createUserWithRole('super_admin');

        $company = Company::factory()->create();
        $response = $this->delete("/api/companies/{$company->id}", [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'company successfully deleted']);
    }

    public function testGetCompany()
    {
        $token = $this->createUserWithRole('super_admin');

        Company::factory()->count(25)->create();
        $response = $this->getJson("/api/companies", [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'email',
                        'phone_number',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ],
                'links'
            ]);
    }

    public function testGetCompanyWithPagingAndSearch()
    {
        $token = $this->createUserWithRole('super_admin');

        Company::factory()->count(25)->create();
        $response = $this->getJson("/api/companies?search=test&sort=asc&limit=1&page=1", [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data', 'links']);
    }
}
