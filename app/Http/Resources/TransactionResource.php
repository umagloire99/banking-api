<?php

namespace App\Http\Resources;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{

    private int $bankAccountId;

    public function __construct($resource, $bankAccountId)
    {
        $this->bankAccountId = $bankAccountId;
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->bankAccountId == $this->source_id) {
            $relatedBankAccount = $this->destination;
        } else {
            $relatedBankAccount = $this->source;
        }
        return [
            'transaction_id' => $this->transaction_id,
            'type' => $this->type,
            'status' => $this->status,
            'amount' => $this->amount,
            'fee' => $this->fee,
            'balance' => $this->bankAccountId == $this->source_id ? $this->source_balance :  $this->destination_balance,
            'role' => $this->bankAccountId == $this->source_id ? 'source': 'destination',
            'reason' => $this->reason,
            'bank_account' => [
                'account_name' => $relatedBankAccount->customer->name,
                'account_number' => $relatedBankAccount->account_number,
            ],
            'date' => $this->created_at
        ];
    }
}
