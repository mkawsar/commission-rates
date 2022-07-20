<?php

namespace Transaction\CommissionFee\Commissions;

use Transaction\CommissionFee\Models\Transaction;
use Transaction\CommissionFee\Services\CurrencyService;

abstract class Commission
{
    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * @var CurrencyService
     */
    protected $currencyService;

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

    protected function getFee($rate, $feeAbleAmount = null)
    {
        $amount = $feeAbleAmount ?? $this->transaction->getAmount();

        return $this->currencyService->getPercentageOfAmount($amount, $rate);
    }
}
