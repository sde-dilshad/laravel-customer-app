<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
// use Laravel\Passport\Passport;
use Tests\TestCase;
use Laravel\Passport\ClientRepository;


class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;
    public $user;
    public $token;
    public function setUp(): void
    {
        parent::setUp();

        $clientRepository = new ClientRepository();
        $clientRepository->createPersonalAccessGrantClient(
            'users',
            'users',
        );

        $email = 'mfauser@example.com';
        $password = 'password123';

        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response = $this->postJson(route('api.mfa.verify'), [
            'email' => $email,
            'code' => '121212'
        ]);

        $this->user = $user;
        $this->token = $response['token'];
    }

    public function test_get_all_customers()
    {
        Customer::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/customers');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'first_name', 'last_name']]]);
    }


    public function test_create_customer()
    {
        $payload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 30,
            'dob' => '1995-01-01',
            'email' => 'john@example.com',
        ];

        $response = $this->postJson('/api/customers', $payload, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['email' => 'john@example.com']);

        $this->assertDatabaseHas('customers', ['email' => 'john@example.com']);
    }


    public function test_show_specific_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}", [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $customer->id]);
    }

    public function test_customer_can_be_updated()
    {
        $customer = Customer::factory()->create([
            'first_name' => 'Old',
            'last_name' => 'Name',
            'email' => 'old@example.com',
            'age' => 40,
            'dob' => '1985-01-01',
        ]);

        $updateData = [
            'first_name' => 'New',
            'last_name' => 'Name',
            'email' => 'new@example.com',
            'age' => 35,
            'dob' => '1990-05-10',
        ];

        $response = $this->putJson("/api/customers/{$customer->id}", $updateData, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'first_name' => 'New',
                'email' => 'new@example.com',
            ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'first_name' => 'New',
            'email' => 'new@example.com',
        ]);
    }

    public function test_customer_can_be_deleted()
    {
        $customer = Customer::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Customer deleted successfully',
            ]);

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }


}
