<?php

use App\Models\BankAccount;
use App\Models\Transaction;
use Spatie\Valuestore\Valuestore;


if (!function_exists('generateTransactionId')) {
    /**
     * generate a unique transaction id
     * @return false|string
     */
    function generateTransactionId(): bool|string
    {
        $chars = '0123456789';
        $transactionId = substr(str_shuffle($chars), 0, 20);
        if (Transaction::whereTransactionId($transactionId)->exists()) {
            return generateTransactionId();
        } else {
            return $transactionId;
        }
    }
}

if (!function_exists('generateAccountNumber')) {
    /**
     * generate a unique account number
     * @return string
     */
    function generateAccountNumber(): string
    {
        $chars = '0123456789';
        $accountNumber = substr(str_shuffle($chars), 0, 14);
        if (BankAccount::whereAccountNumber($accountNumber)->exists()) {
            return generateAccountNumber();
        } else {
           return "1$accountNumber";
        }
    }
}

if (!function_exists('getSettingsOf')) {
    /**
     * get platform settings value from a key
     * @param string $key
     * @return array|string|null
     */
    function getSettingsOf(string $key): array|string|null
    {
        $settings = Valuestore::make(config_path('platform_settings.json'));
        return $settings->get($key);
    }
}

