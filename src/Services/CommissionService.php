<?php

namespace Transaction\CommissionFee\Services;

use Transaction\CommissionFee\Commissions\CommissionTypeInterface;
use Transaction\CommissionFee\Commissions\Types\DepositCommission;
use Transaction\CommissionFee\Commissions\Types\WithdrawalBusinessCommission;
use Transaction\CommissionFee\Commissions\Types\WithdrawPrivateCommission;
use Transaction\CommissionFee\Exceptions\InvalidOperationTypeException;
use Transaction\CommissionFee\Exceptions\InvalidUserTypeException;
use Transaction\CommissionFee\Models\Transaction;
use Transaction\CommissionFee\TransactionCollection;

class CommissionService
{
    /**
     * @var TransactionCollection
     */
    protected TransactionCollection $transactionCollection;

    /**
     * @var CurrencyService
     */
    protected CurrencyService $currencyService;

    /**
     * CommissionCalculator constructor.
     *
     * @param CurrencyService $currencyService
     */

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @param TransactionCollection $collection
     *
     * @return array
     */
    public function calculateFeesFromCollection(TransactionCollection $collection): array
    {
        $fees = [];
        foreach ($collection->getTransactions() as $transaction) {
            $commission = $this->generateCommission($this->currencyService, $transaction, $collection);
            $fees[] = $this->currencyService->roundAndFormat($commission->calculate());
        }

        return $fees;
    }

    public function generateCommission(
        CurrencyService       $currencyService,
        Transaction           $transaction,
        TransactionCollection $transactionCollection
    ): CommissionTypeInterface
    {

        switch ($transaction->getOperationType()) {
            case 'deposit':
                $commission = new DepositCommission($transaction, $currencyService);
                break;
            case 'withdraw':
                switch ($transaction->getUserType()) {
                    case 'private':
                        $commission = new WithdrawPrivateCommission($transaction, $currencyService, $transactionCollection);
                        break;
                    case 'business':
                        $commission = new WithdrawalBusinessCommission($transaction, $currencyService);
                        break;
                    default:
                        throw new InvalidUserTypeException;
                }
                break;
            default:
                throw new InvalidOperationTypeException;
        }

        return $commission;
    }

}
