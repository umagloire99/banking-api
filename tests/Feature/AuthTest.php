<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;


    /**
     * test invalid input user registration
     *
     * @return void
     */
    public function test_invalid_input_user_registration()
    {
        $response = $this->post('/api/register', [
            'name'=> null,
            'email' => 'test',
            'password' => null,
        ]);
        $response->assertStatus(400);
    }

    /**
     * success user registration
     */
    public function test_success_registration()
    {
        $response = $this->post('/api/register', [
            'name'=> $this->faker->name,
            'email' => $this->faker->email(),
            'password' => $this->faker->password,
        ]);
        $response->assertStatus(201);
    }

    /**
     * test success login
     */
    public function test_success_login()
    {
        User::create([
            'name' => $this->faker->name,
            'email' => 'johndoe@test.com',
            'password' => Hash::make('password')
        ]);
        $response = $this->post('/api/login', [
            'email' => 'johndoe@test.com',
            'password' => 'password',
        ]);
        $response->assertOk();
    }
}
