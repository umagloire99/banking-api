<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use WithFaker;

    protected BankAccount $source;
    protected BankAccount $destination;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

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
        $this->createFakeBankAccount();
    }

    /**
     * test insufficient balance
     */
    public function test_insufficient_balance()
    {
        $response = $this->post("api/bank-accounts/" . $this->source->account_number . "/transfer", [
            'amount' => 20000,
            'account_number' => $this->destination->account_number
        ]);
        $response->assertStatus(406);
        $response->assertJson(['message' => __('general.insufficient_balance')]);
    }

    /**
     * try to send money to an account that doesn't exit
     */
    public function test_no_bank_account()
    {
        $response = $this->post("api/bank-accounts/" . $this->source->account_number . "/transfer", [
            'amount' => 20000,
            'account_number' => '11111111111'
        ]);
        $response->assertStatus(406);
        $response->assertJson(['message' => __('general.no_bank_account')]);
    }

    /**
     * check successful transfer transaction
     */
    public function test_success_transfer()
    {
        $response = $this->post("api/bank-accounts/" . $this->source->account_number . "/transfer", [
            'amount' => 1000,
            'account_number' => $this->destination->account_number,
            'reason' => 'School Fee'
        ]);
        $response->assertOk();
        $json = $response->json();
        $transaction = $json['transaction'];
        $this->assertNotEquals($transaction['balance'], $this->source->balance - (1000 - (int)getSettingsOf('transfer_fee')));
    }

    /**
     * test to get transaction history of a bank account
     */
    public function test_bank_account_history()
    {
        $response = $this->get("api/bank-accounts/" . $this->source->account_number . "/history");
        $response->assertOk();
    }

    protected function createFakeBankAccount()
    {
        $customer = Customer::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'dob' => '2000-08-08',
            'address' => $this->faker->address,
            'phone' => '+237670000000',
            'user_id' => 1
        ]);
        $this->source = new BankAccount();
        $this->source->account_number = generateAccountNumber();
        $this->source->balance = 5000;
        $this->source->customer()->associate($customer);
        $this->source->save();

        $this->destination = new BankAccount();
        $this->destination->account_number = generateAccountNumber();
        $this->destination->balance = 5000;
        $this->destination->customer()->associate($customer);
        $this->destination->save();
    }
}
