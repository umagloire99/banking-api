<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BankAccountTest extends TestCase
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
     * get all bank accounts
     *
     * @return void
     */
    public function test_to_get_all_bank_accounts()
    {
        $response = $this->get('/api/bank-accounts');
        $response->assertStatus(200);
    }

    /**
     * test bank account creation and get bank account balance
     */
    public function test_customer_bank_account() {
        $customer = Customer::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'dob' => '2000-08-08',
            'address' => $this->faker->address,
            'phone' => '+237670000000',
            'user_id' => 1
        ]);
        $response = $this->post('/api/bank-accounts', [
            'customer_id' => $customer->id
        ]);
        $response->assertStatus(201);
        $json = $response->json();

        $account_number = $json['account_number'];
        $response = $this->get("/api/bank-accounts/$account_number/balance");
        $response->assertStatus(200);
    }

}
