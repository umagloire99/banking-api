<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\BankAccountResource;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * get all customers
     * @return Response|Application|ResponseFactory
     */
    public function index(): Response|Application|ResponseFactory
    {
        $customers = Customer::all();
        return response(CustomerResource::collection($customers));
    }

    /**
     * create a customer
     * @param CustomerRequest $request
     * @return Response|Application|ResponseFactory
     */
    public function store(CustomerRequest $request): Response|Application|ResponseFactory
    {
        $input = $request->only('name', 'email','phone', 'dob', 'address');
        $input['user_id'] = $request->user()->id;
        $customer = Customer::create($input);
        return response(new CustomerResource($customer), 201);
    }

    /**
     * get all customer bank accounts
     * @param $customerId
     * @return Response|Application|ResponseFactory
     */
    public function getBankAccounts($customerId): Response|Application|ResponseFactory
    {
        $customer = Customer::whereId($customerId)->first();
        if ($customer) {
            return response(BankAccountResource::collection($customer->bankAccounts));
        } else {
            return response(['message' => __('general.no_customer')], 406);
        }
    }
}
