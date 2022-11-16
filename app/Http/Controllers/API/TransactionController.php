<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\TransactionResource;
use App\Models\BankAccount;
use App\Models\Transaction;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * get list of transactions of a given bank account
     * @param $accountNumber
     * @return Response|Application|ResponseFactory
     */
    public function index($accountNumber): Response|Application|ResponseFactory
    {
        $bankAccount = BankAccount::whereAccountNumber($accountNumber)->first();
        if ($bankAccount) {
            $transactions = Transaction::byBankAccount($bankAccount->id)->paginate()
                ->through(function (Transaction $transaction) use ($bankAccount) {
                    return new TransactionResource($transaction, $bankAccount->id);
                });
            return response($transactions->items());
        } else {
            return response([
                'message' => __('general.no_bank_account')
            ], 406);
        }
    }

    /**
     * transfer amount between two bank accounts
     * @param TransferRequest $request
     * @param $accountNumber
     * @return Response|Application|ResponseFactory
     */
    public function transfer(TransferRequest $request, $accountNumber): Response|Application|ResponseFactory
    {
        $max_transfer = (int)getSettingsOf('max_transfer_amount_per_day');
        $fee = getSettingsOf('transfer_fee');
        $amount = $request->get('amount') + $fee;

        if ($bankAccount = BankAccount::whereAccountNumber($accountNumber)->first()) {
            $receiverBankAccount = BankAccount::whereAccountNumber($request->get('account_number'))->first();
            if (!$receiverBankAccount) {
                return response([
                    'message' => __('general.no_bank_account')
                ], 406);
            }

            if ($receiverBankAccount->account_number == $accountNumber) {
                return response([
                    'message' => __('general.same_bank_account')
                ], 406);
            }

            // check transfer amount limitation per day
            $remaining_transfer_amount = $max_transfer - (int)Transaction::totalTransferAmountPerDay($bankAccount->id);
            if ($remaining_transfer_amount < $amount) {
                $remaining_transfer_amount = number_format($remaining_transfer_amount);
                return response([
                    'message' => __('general.transfer_limitation_per_day', ['amount'=>$remaining_transfer_amount])
                ], 406);
            }

            // check bank account balance
            if ($bankAccount->balance < $amount) {
                return response([
                    'message' => __('general.insufficient_balance')
                ], 406);
            }

            DB::beginTransaction();
            $transaction = new Transaction();
            $transaction->status = 'complete';
            $transaction->amount = $request->get('amount');
            $transaction->fee = $fee;
            $transaction->type = 'fund_transfer';
            $transaction->source_id = $bankAccount->id;
            $transaction->source_balance = $bankAccount->balance - $amount;
            $transaction->destination_id = $receiverBankAccount->id;
            $transaction->destination_balance = $receiverBankAccount->balance + $request->get('amount');
            $transaction->transaction_id = generateTransactionId();
            $transaction->reason = $request->get('reason');
            $transaction->save();

            // update sender bank account balance
            $bankAccount->balance = $transaction->source_balance;
            $bankAccount->save();

            // update receiver bank account balance
            $receiverBankAccount->balance = $transaction->destination_balance;
            $receiverBankAccount->save();
            DB::commit();

            return response([
               'message' => __('general.transfer_sent'),
               'transaction' => new TransactionResource($transaction, $bankAccount->id)
            ]);
        } else {
            return response([
                'message' => __('general.no_bank_account')
            ], 406);
        }
    }
}
