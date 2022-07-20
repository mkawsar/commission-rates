<?php

namespace Transaction\CommissionFee\Commissions\Types;

use Transaction\CommissionFee\Commissions\Commission;
use Transaction\CommissionFee\Commissions\CommissionTypeInterface;
use Transaction\CommissionFee\Models\Amount;
use Transaction\CommissionFee\Models\Transaction;
use Transaction\CommissionFee\Services\CurrencyService;
use Transaction\CommissionFee\TransactionCollection;

class WithdrawPrivateCommission extends Commission implements CommissionTypeInterface
{
    /**
     * @var float
     */
    const COMMISSION_PERCENTAGE = 0.3;

    /**
     * @var array
     */
    const WEEKLY_FREE_CHARGE_LIMIT = [
        'currency' => 'EUR',
        'amount' => 1000,
        'maxOperations' => 3
    ];

    /**
     * @var Transaction[]
     */
    protected $transactionHistory;

    public function __construct(Transaction $transaction, CurrencyService $currencyService, TransactionCollection $transactionCollection)
    {
        $this->transactionHistory = $transactionCollection;
        parent::__construct($transaction, $currencyService);
    }

    /**
     * @return Amount
     */
    public function calculate()
    {
        $summary = $this->getWeeklyCashOutSummaryOfUser();

        // user has no available free charge limit or allowed for free operation
        if ($summary['availableFreeChargeLimit']->getAmount() <= 0 || $summary['maximumOperationLimitIsReached']) {
            $commission = $this->getFee(self::COMMISSION_PERCENTAGE);
        } else {
            // user has enough limit, free charge
            if ($summary['availableFreeChargeLimit']->getAmount() >= $this->transaction->getAmount()->getAmount()) {
                $commission = new Amount(0, $this->transaction->getAmountSymbol());
            } else {
                // charge for only exceeded amount
                $exceededAmount = $this->currencyService->subAmount(
                    $this->transaction->getAmount(),
                    $summary['availableFreeChargeLimit'],
                    $this->transaction->getAmountSymbol()
                );
                $commission = $this->getFee(self::COMMISSION_PERCENTAGE, $exceededAmount);
            }
        }

        return $commission;
    }

    /**
     * @return array
     */
    private function getWeeklyCashOutSummaryOfUser()
    {
        // filter the users transactions in same week and same type
        $weeklyTransactions = $this->getLastWeeksTransactions();

        // calculate operation count and total amount of cash out in same week
        $totalAmount = new Amount(0, $this->transaction->getAmountSymbol());
        $operationCount = 0;
        foreach ($weeklyTransactions as $transaction) {
            $totalAmount = $this->currencyService->sumAmounts(
                $totalAmount,
                $transaction->getAmount(),
                $this->transaction->getAmountSymbol()
            );
            $operationCount++;
        }

        // calculate available free charge limit
        $maxLimit = new Amount(self::WEEKLY_FREE_CHARGE_LIMIT['amount'], self::WEEKLY_FREE_CHARGE_LIMIT['currency']);
        $availableFreeChargeLimit = $this->currencyService->subAmount(
            $maxLimit,
            $totalAmount,
            $this->transaction->getAmountSymbol()
        );

        $maximumOperationLimitIsReached = $operationCount >= self::WEEKLY_FREE_CHARGE_LIMIT['maxOperations'];

        return compact('maximumOperationLimitIsReached', 'availableFreeChargeLimit');
    }

    /**
     * @return Transaction[]
     */
    private function getLastWeeksTransactions()
    {
        return array_filter($this->transactionHistory->getTransactions(), function (Transaction $transaction) {
            return
                // must be older than this transaction
                $transaction->getTransactionID() < $this->transaction->getTransactionID()
                &&
                // processed in same week with this transaction
                $transaction->getTransactionDate()->format('oW') ===
                $this->transaction->getTransactionDate()->format('oW')
                &&
                // having same operation type with this transaction
                $transaction->getOperationType() === $this->transaction->getOperationType()
                &&
                // having the same id with this transaction
                $transaction->getIdentificationNumber() === $this->transaction->getIdentificationNumber();
        });
    }
}
