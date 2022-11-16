<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make('password')
        ]);
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getBearerToken($user),
        ]);
    }

    /**
     * test customer registration with invalid input
     */
    public function test_invalid_input_customer_registration()
    {
       $response = $this->post('/api/customers', [
           'name' => $this->faker->name,
           'email' => $this->faker->name,
       ]);
       $response->assertStatus(400);
    }

    /**
     * success customer registration
     */
    public function test_success_customer_registration()
    {
        $response = $this->post('/api/customers', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'dob' => '2000-08-08',
            'address' => $this->faker->address,
            'phone' => '+237670000000'
        ]);
        $response->assertStatus(201);
    }

}
