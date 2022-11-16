<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BankAccountResource;
use App\Models\BankAccount;
use App\Models\Customer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BankAccountController extends Controller
{
    /**
     * get list of user bank Accounts
     * @return Response|Application|ResponseFactory
     */
    public function index(): Response|Application|ResponseFactory
    {
        return response(BankAccountResource::collection(BankAccount::all()));
    }

    /**
     * detail of a bank account
     * @param $accountNumber
     * @return Response|Application|ResponseFactory
     */
    public function getBalance($accountNumber): Response|Application|ResponseFactory
    {
        $bankAccount = BankAccount::whereAccountNumber($accountNumber)->first();
        if ($bankAccount) {
            return response(['account_number' => $bankAccount->account_number, 'balance' => $bankAccount->balance,]);
        } else {
            return response([
                'message' => __('general.no_bank_account')
            ], 406);
        }
    }

    /**
     * create a bank account
     * @param Request $request
     * @return Response|Application|ResponseFactory
     */
    public function store(Request $request): Response|Application|ResponseFactory
    {
        $customer = Customer::whereId($request->get('customer_id'))->first();
        $max_bank_account = (int)getSettingsOf('max_bank_account');
        if ($customer) {
            if ($customer->bankAccounts()->count() < $max_bank_account) {
                $bankAccount = new BankAccount();
                $bankAccount->account_number = generateAccountNumber();
                $bankAccount->balance = 50000;
                $bankAccount->customer()->associate($customer);
                $bankAccount->save();
                return response(new BankAccountResource($bankAccount), 201);
            } else {
                return response([
                    'message' => __('general.max_bank_accounts_reached')
                ], 406);
            }
        } else {
            return response(['message' => __('general.no_customer')], 406);
        }
    }
}
