<?php

namespace Transaction\CommissionFee\Commissions;

use Transaction\CommissionFee\Models\Amount;
use Transaction\CommissionFee\Models\Transaction;
use Transaction\CommissionFee\Services\CurrencyService;

abstract class Commission
{
    /**
     * @var Transaction
     */
    protected Transaction $transaction;

    /**
     * @var CurrencyService
     */
    protected CurrencyService $currencyService;

    /**
     * Commission constructor.
     *
     * @param Transaction $transaction
     * @param CurrencyService $currencyService
     */
    public function __construct(Transaction $transaction, CurrencyService $currencyService)
    {
        $this->transaction = $transaction;
        $this->currencyService = $currencyService;
    }

    protected function getFee($rate, $feeAbleAmount = null): Amount
    {
        $amount = $feeAbleAmount ?? $this->transaction->getAmount();

        return $this->currencyService->getPercentageOfAmount($amount, $rate);
    }
}
