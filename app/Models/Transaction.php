<?php

namespace App\Models;

use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Initiator of the transaction
     * @return BelongsTo
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * Benefactor of the transaction
     * @return BelongsTo
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * get transactions related to a specific account
     * @param $query
     * @param $bankAccountId
     * @return QueryBuilder
     */
    public function scopeByBankAccount($query, $bankAccountId)
    {
        return $query->where('source_id', $bankAccountId)->orWhere('destination_id', $bankAccountId)
            ->orderBy('created_at', 'DESC');
    }

    /**
     * get total amount of transfer per day done by a bank account
     * @param $query
     * @param $bankAccountId
     * @return mixed
     */
    public function scopeTotalTransferAmountPerDay($query, $bankAccountId)
    {
        return $query->where('source_id', $bankAccountId)
            ->whereDate('created_at', date('Y-m-d'))
            ->sum('amount');
    }
}
